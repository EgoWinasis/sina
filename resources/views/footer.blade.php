@section('footer')
    @php
        $tahun = '2024';
    @endphp
    @if (date('Y') == $tahun)
        <div id="mycredit" class="small"><strong> Copyright &copy;
                <?= date('Y'); ?> IT BPR Nusamba Adiwerna <a href="https://www.instagram.com/egowinasis/" target="_blank"
                    rel="noopener noreferrer">(@EgoWinasis)</a>
        </div>
    @else
        <div id="mycredit" class="small"><strong> Copyright &copy;
                <?= $tahun.'-'.date('Y'); ?> IT BPR Nusamba Adiwerna <a href="https://www.instagram.com/egowinasis/" target="_blank"
                    rel="noopener noreferrer">(@EgoWinasis)</a>
        </div>
    @endif

@stop
