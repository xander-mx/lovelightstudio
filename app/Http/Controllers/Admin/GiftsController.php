<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Gifts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GiftsController extends Controller
{

  const CANVA = 'CANVA';
  const PHOTOBOOK = 'PHOTOBOOK';

  public function save(Request $request) {
    try{
      DB::beginTransaction();
      $data = $request->all();
      Events::find($data['id'])->update(['address' => $data['address']]);
      $exists = Gifts::where('id_event', $data['id'])->get();
      if(sizeof($exists) == 0) {
        foreach ($data as $key => $field) {
          if(strrpos($key, "gift") !== false) {
            $gift = new Gifts();
            $gift->id_event = $data['id'];
            $gift->photos = $field;
            $gift->type = str_contains($key, 'photobook') ? self::PHOTOBOOK : self::CANVA;
            $gift->save();
          }
        }
      }
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollBack();
      $error = '';
      if(isset($th->validator) && sizeOf($th->validator->messages()) > 0) {
        foreach ($th->validator->messages()->all() as $message)
          $error .= $message. ' ';
      }  else {
        $error = $th->getMessage();
      }
      return response()->json(['message' => $error, 'code' => 401]);
    }
    return view('system.weddings.events.thanks');
  }
}