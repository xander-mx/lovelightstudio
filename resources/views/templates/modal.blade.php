
@php
  $backdrop = $backdrop ?? false;
  $close = $close ?? false;
  $modalSize = $modalSize ?? ''
@endphp

<div class="modal fade " id="{{ $modalId }}" @if($backdrop) data-backdrop="static" @endif data-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ $modalSize }}">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"> {{ $title ?? '' }}</h5>
                @isset($close)
                    <button type="button" class="close" data-dismiss="modal" onclick="@if(isset($clickClose)){{$clickClose}} @else closeAnyModal($('#{{ $modalId }}')); @endif" aria-label="Close" style="cursor: pointer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                @endisset
            </div>
            <div class="modal-body">
                {!! $body !!}
            </div>
            <div class="modal-footer text-center" @isset($styleFooter)style="{{ $styleFooter }}"@endisset>
                @if(isset($footer))
                    {!! $footer !!}
                @endif
            </div>
        </div>
    </div>
</div>