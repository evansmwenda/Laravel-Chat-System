<div class="max-w-6xl mx-auto my-16">
    <h5 class="text-center text-5xl font-bold py-3">Users</h5>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 p-2 mb-5">

        @foreach ($users as $key=> $user)
            
        <div class="bg-white border border-gray-200 rounded-lg p-5 shadow mt-3">

            <div class="flex flex-col items-center pb-10 ml-5">
                <img src="https://source.unsplash.com/200x200?face-{{$key}}" alt="image" class="w-11 h-11 mb-2 mt-2  5 rounded-full shadow-lg">

                <h5 class="mb-1 text-xl font-medium text-gray-900 " >
                    {{$user->name}}
                </h5>
                <span class="text-sm text-gray-500">{{$user->email}} </span>

                <div class="flex mt-4 space-x-3 md:mt-6 py-5">
                    <x-primary-button wire:click="message({{$user->id}})" class="text-white hover:bg-green-700 transition duration-300 ease-in-out">
                        Message
                    </x-primary-button>
                </div>
            </div>
        </div>

        @endforeach
    </div>




</div>
