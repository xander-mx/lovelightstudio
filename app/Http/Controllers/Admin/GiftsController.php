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
      $rules = [
        'id' => 'required',
        'address' => 'required'
      ];
      $messages = [
        'id' => 'El evento es requerido!!!',
        'address' => 'El domiclio es requerido!!!'
      ];
      DB::beginTransaction();
      $this->validate($request, $rules, $messages);
      $data = $request->all();
      throw_if(Events::where('id', $data['id'])->where('code', $data['code'])->count() < 1,
        'No existe evento!!');
      Events::where('id', $data['id'])->where('code', $data['code'])->update(['address' => $data['address']]);
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
        $gifts[] = $this->generateInputCanva("canvaaddress", "Domicilio", "bi bi-house-heart",
        $event->address ?? '') ;
      foreach ($event->gifts as $gift) {
        if($gift->type == self::CANVA) {
          $input = $this->generateInputCanva("giftcanva".$canva, "Canva ".$canva, 'bi bi-images', $gift->photos);
          $canva++;
        } else {
          $input = $this->generateInputCanva("giftphotobook".$book, "Photobook ".$book, 'bi bi-journal-bookmark', $gift->photos);
          $book++;
        }
        $gifts[] = $input;
      }
      if(sizeof($gifts) == 0) {
        for($x = 0; $x < 2; $x++) {
          $gifts[] = $this->generateInputCanva("giftcanva".$x, ($x > 0 ? "Photobook" : "Canva"),
            ($x > 0 ? "bi-journal-bookmark" : "bi-images"), '');
        }
      }
      return response()->json(['data' => $gifts, 'code' => 200]);
    }catch (\Throwable $th) {
      return response()->json(['message' => $th->getMessage(), 'code' => 401]);
    }
  }

  private function generateInputCanva(String $id, String $text, String $icon, String $value) {
    $input = "<div class='form-group col-12'>";
    $input .= "<label for='".$id."'>".$text."</label>";
    $input .= "<div class='input-group'><span class='input-group-text'><i class='".$icon."'></i></span>";
    $input .= "<input name='".$id."' id='".$id."'";
    $input .= " type='text' class='form-control' value='".$value."' disabled /></div></div>";
    return $input;
  }
}