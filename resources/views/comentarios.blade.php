<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.6.0/dist/alpine.js" defer></script>

<!-- PARA EL DASHBOARD -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss/dist/tailwind.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <style>
        .bg-side-nav {
             background-color: #ECF0F1;
            }

    </style>


        <link href='{{asset('js/calendario/main.css')}}' rel='stylesheet' />
        <script src='{{asset('js/calendario/main.js')}}'></script>
        <script src='{{asset('js/calendario/es.js')}}'></script>
    
    </head>
<body>
    <div class="flex flex-col w-full">
    <div class="flex flex-row bg-blue-900 py-5 px-5 content-center space-x-10">   
        <div>
            <image src="{{url('images/bca.jpg') }}" class="w-12 rounded-lg shadow-2xl"></image>
        </div>
        <div class="flex flex-col">
            <div class="text-white text-xl"> 
                Notas de Seguimiento
            </div>
        </div>
    </div>
    <div class="w-full flex flex-col p-4 justify-center">
        <div class="w-1/2">Registrar nota</div>
        <form action="{{route('comentarios')}}" method="POST">
            @csrf
            <div class="w-full">
                <textarea class="w-full rounded p-1 border border-gray-300" name="comentario">{{old('comentario')}}</textarea>
                @error('comentario')
                  <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                @enderror                    
            </div>
            <div class="w-full flex justify-center py-4">
                <button class="rounded p-1 border bg-green-500 hover:bg-green-700 text-gray-100 font-semibold">Guardar</button>
            </div>
        </form>
        @if(session('status')!='')
            <div class="w-full flex justify-center p-3 bg-green-300 rounded-b-lg">
                <span class="font-semibold text-sm text-gray-600">{{session('status')}}</span>
            </div>    
        @endif
        <div class="w-full flex justify-center"><center>
            <table>
                <tr class="bg-gray-200 text-base">
                    <td class="px-3 py-1">Nota</td>
                    <td class="px-3 py-1">Autor</td>
                    <td class="px-3 py-1">Creada en</td>
                </tr>
                @foreach ($comentarios as $comentario)
                <tr class="border-b text-sm">
                    <td class="px-3 py-1">{{$comentario->comentario}}</td>
                    <td class="px-3 py-1">{{App\Models\User::where('empleado',$comentario->empleado)->get()->first()->name}}</td>
                    <td class="px-3 py-1">{{$comentario->created_at}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

</body>
