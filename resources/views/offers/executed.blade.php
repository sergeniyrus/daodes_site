
    {{-- <div class="left_box">
        @auth
            <?php
    //         $user = Auth::user();

    //         $id_offer = $id_offer ?? request()->route('id');

    //         $pdfIpfsCid = DB::table('offers')
    // ->where('id', $id_offer)
    // ->value('pdf_ipfs_cid');
            ?>

            @if(isset($pdfIpfsCid))
                <div class="login_prompt" style="text-align: center">Решение в 
                  <a href="https://daodes.space/ipfs/{{ $pdfIpfsCid }}" target="_blank" style="color: gold">IPFS</a>
                </div>
            @else
                <div class="msg">Решение в IPFS отсутствует</div>
            @endif
        @else
            <div class="msg">Необходимо <a href="/login" class="eror_com">войти</a></div>
        @endauth
    </div> --}}
    <div class="left_box">
        @auth
            <?php
            $user = Auth::user();
    
            $id_offer = $id_offer ?? request()->route('id');
    
            $pdfIpfsCid = DB::table('offers')
                ->where('id', $id_offer)
                ->value('pdf_ipfs_cid');
            ?>
    
            @if(isset($pdfIpfsCid))
                <div class="login_prompt" style="text-align: center">Decision available in 
                    <a href="https://daodes.space/ipfs/{{ $pdfIpfsCid }}" target="_blank" style="color: gold">IPFS</a>
                </div>
            @else
                <div class="msg">Decision is not available in <a href="https://daodes.space/ipfs/{{ $pdfIpfsCid }}" target="_blank" style="color: gold">IPFS</a></div>
            @endif
        @else
            <div class="msg">You need to <a href="/login" class="eror_com">log in</a></div>
        @endauth
    </div>