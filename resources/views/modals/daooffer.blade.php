<style>

/* Модальное окно */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1000;
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-container {
    background: #1e1e1e;
    color: #fff;
    border-radius: 8px;
    width: 80%;
    max-width: 800px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.5rem;
}

.modal-close {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.8rem;
    cursor: pointer;
    padding: 0 10px;
}

.modal-content {
    padding: 20px;
    overflow-y: auto;
    flex-grow: 1;
}

.agreement-text h4 {
    color: #4a90e2;
    margin-top: 20px;
}

.modal-footer {
    padding: 15px 20px;
    border-top: 1px solid #333;
    text-align: right;
}

.modal-button {
    background: #4a90e2;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 4px;
    cursor: pointer;
}

/* Чекбокс соглашения
.agreement-check {
    margin: 20px 0;
    padding: 10px;
    background: rgba(30, 30, 30, 0.1);
    border-radius: 4px;
}

.agreement-check a {
    color: #4a90e2;
    text-decoration: underline;
} */

</style>

<div class="modal-overlay" id="daoAgreementModal">
    <div class="modal-container">
        <div class="modal-header">
            <h3>{{ __('agreement.title') }}</h3>
            <button class="modal-close" onclick="closeModal()">{{ __('agreement.buttons.close') }}</button>
        </div>
        <div class="modal-content">
            <div class="agreement-text">
                @foreach(__('agreement.sections') as $section)
                    <h4>{{ $section['title'] }}</h4>
                    
                    @isset($section['items'])
                        @foreach($section['items'] as $item)
                            <p>{{ $item }}</p>
                        @endforeach
                    @endisset
                    
                    @isset($section['requirements'])
                        <p>{{ $section['requirements'] }}</p>
                        <ul class="agreement-list">
                            @foreach($section['requirements_items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endisset
                    
                    @isset($section['guarantees'])
                        <p>{{ $section['guarantees'] }}</p>
                        <ul class="agreement-list">
                            @foreach($section['guarantees_items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    @endisset
                    
                    @isset($section['disclaimer'])
                        <p>{{ $section['disclaimer'] }}</p>
                        <ul class="agreement-list">
                            @foreach($section['items'] as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                        <p>{{ $section['responsibility'] }}</p>
                    @endisset
                @endforeach
            </div>
        </div>
        {{-- <div class="modal-footer">
            <button class="modal-button" onclick="declineAgreement()">{{ __('agreement.buttons.decline') }}</button>
            <button class="modal-button" onclick="acceptAgreement()">{{ __('agreement.buttons.accept') }}</button>
        </div> --}}
    </div>
</div>