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
            <div class="login_prompt" style="text-align: center">
                {!! __('offers.decision_available', ['link' => "https://daodes.space/ipfs/$pdfIpfsCid"]) !!}
                <div>
                @php
                    echo $pdfIpfsCid;
                @endphp    
                    </div>
            </div>
        @else
            <div class="msg">
                {!! __('offers.decision_not_available', ['link' => "https://daodes.space/ipfs/$pdfIpfsCid"]) !!}
            </div>
        @endif
    @else
        <div class="msg">
            {!! __('offers.login_to_view', ['login_link' => route('login')]) !!}
        </div>
    @endauth
</div>