<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\Gifts;
use Illuminate\Http\JsonResponse;
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

  public function getGiftsInfo(int $id): JsonResponse
  {
    try {
      $gifts = [];
      $canva = 1;
      $book = 1;
      $event = Events::find($id);
      foreach ($event->gifts as $gift) {
        $input = "<div class='form-group col-12'>";
        if($gift->type == self::CANVA) {
          $input .= "<label for='giftcanva".$canva."'>Canva ".$canva."</label>";
          $input .= "<div class='input-group'><span class='input-group-text'><i class='bi bi-images'></i></span>";
          $input .= "<input name='giftcanva".$canva."' id='giftcanva".$canva."'";

          $canva++;
        } else {
          $input .= "<label for='giftphotobook".$book."'>Photobook ".$book."</label>";
          $input .= "<div class='input-group'><span class='input-group-text'><i class='bi bi-journal-bookmark'></i></span>";
          $input .= "<input name='giftphotobook".$book." id='giftphotobook".$book."'";
          $book++;
        }
        $input .= " type='text' class='form-control' value='".$gift->photos."' disabled /></div></div>";
        $gifts[] = $input;
      }
      if(sizeof($gifts) == 0) {
        for($x = 0; $x < 2; $x++) {
          $label = ($x > 0 ? "Photobook" : "Canva");
          $icon = ($x > 0 ? "bi-journal-bookmark" : "bi-images");
          $emptyInput = "<div class='form-group col-12'><label for='giftcanva'>".$label."</label>";
          $emptyInput .= "<div class='input-group'><span class='input-group-text'><i class='bi ".$icon."'></i></span>";
          $emptyInput .= "<input name='giftcanva".$x."' id='giftcanva".$x."' type='text' class='form-control' disabled /></div></div>";
          $gifts[] = $emptyInput;
        }
      }
      return response()->json(['data' => $gifts, 'code' => 200]);
    }catch (\Throwable $th) {
      return response()->json(['message' => $th->getMessage(), 'code' => 401]);
    }
  }
}