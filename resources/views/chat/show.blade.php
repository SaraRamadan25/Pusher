@extends('layouts.app')

@push('styles')
    <style type="text/css">
        #users > li {
            cursor: pointer;
            color: red;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Chat</div>

                    <div class="card-body">
                        <div class="row p-2">
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-12 border rounded-lg p-3">
                                        <ul
                                            id="messages"
                                            class="list-unstyled overflow-auto"
                                            style="height: 45vh"
                                        >
                                        </ul>
                                    </div>
                                </div>
                                <form>
                                    <div class="row py-3">
                                        <div class="col-10">
                                            <input id="message" class="form-control" type="text">
                                        </div>
                                        <div class="col-2">
                                            <button id="send" type="submit" class="btn btn-primary btn-block">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-2">
                                <p><strong>Online Now</strong></p>
                                <ul
                                    id="users"
                                    class="list-unstyled overflow-auto text-info"
                                    style="height: 45vh"
                                >
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script type="module">
    const usersElement = document.getElementById('users');
    const messagesElement = document.getElementById('messages');

    function greetUser(id) {
        window.axios.post('/chat/greet/' + id);
    }

    function appendMessage(message) {
        const element = document.createElement('li');
        element.innerText = message;
        messagesElement.appendChild(element);
    }

    window.onload = function () {
        Echo.join('chat')
            .here((users) => {
                users.forEach((user) => {
                    const element = document.createElement('li');
                    element.setAttribute('id', user.id);
                    element.innerText = user.name;

                    element.addEventListener('click', () => {
                        greetUser(user.id);
                    });

                    usersElement.appendChild(element);
                });
            })
            .joining((user) => {
                const element = document.createElement('li');
                element.setAttribute('id', user.id);
                element.innerText = user.name;

                element.addEventListener('click', () => {
                    greetUser(user.id);
                });

                usersElement.appendChild(element);
            })
            .leaving((user) => {
                const element = document.getElementById(user.id);
                if (element) {
                    element.parentNode.removeChild(element);
                }
            })
            .listen('MessageSent', (e) => {
                appendMessage(e.user.name + ': ' + e.message);
            });
    }
</script>

<script type="module">
    const messageElement = document.getElementById('message');
    const sendElement = document.getElementById('send');

    sendElement.addEventListener('click', (e) => {
        e.preventDefault();
        window.axios.post('/chat/message', {
            message: messageElement.value,
        });
        messageElement.value = '';
    });
</script>

<script>
    window.onload = function () {
        Echo.private('chat.greet.{{ auth()->user()->id }}')
            .listen('GreetingSent', (e) => {
                appendMessage(e.message);
                const element = document.getElementById('{{ auth()->user()->id }}');
                if (element) {
                    element.click();
                }
            });
    }
</script>
