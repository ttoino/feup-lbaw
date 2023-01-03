@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column narrow mx-auto my-auto p-3 gap-3">
        <header class="text-center">
            <h2 class="h1">Contacts</h2>
        </header>

        <div class="d-flex flex-row gap-3 flex-wrap">
            <div class="d-flex flex-column">
                <h3><i class="bi bi-envelope"></i> Email:</h3>
                <p>
                    <a href="mailto:up201905517@edu.fe.up.pt"><strong>Beatriz Maia da
                            Silva
                            Cruz</strong> <br>
                        up201905517@edu.fe.up.pt </a><br>
                    <a href="mailto:up202007145@edu.fe.up.pt"><strong>João António
                            Semedo
                            Pereira</strong> <br>
                        up202007145@edu.fe.up.pt</a><br>
                    <a href="mailto:up202007865@edu.fe.up.pt"><strong>Nuno Afonso
                            Anjos
                            Pereira</strong> <br>
                        up202007865@edu.fe.up.pt </a><br>
                    <a href="mailto:up202004714@edu.fe.up.pt"><strong>Pedro Miguel
                            Magalhães
                            Nunes</strong> <br>
                        up202004714@edu.fe.up.pt </a>
                </p>
            </div>
            <div class="d-flex flex-column">
                <h3><i class="bi bi-geo-alt"></i> Location</h3> <br>
                <p>
                    Faculdade de Engenharia (FEUP)<br>
                    Rua Dr. Roberto Frias<br>
                    4200-465 PORTO<br>
                </p>
            </div>
        </div>
    </div>
@endsection
