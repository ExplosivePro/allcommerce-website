<ul>
    @foreach($checklist as $col => $item)
        @if($item['crossed'])
            <li><s>{!! $item['instruction'] !!}</s></li>
        @else
            <li>{!! $item['instruction'] !!}</li>
        @endif
    @endforeach
</ul>
