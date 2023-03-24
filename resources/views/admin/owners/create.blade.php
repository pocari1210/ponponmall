<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            SHOP登録
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">

        <section class="text-gray-600 body-font relative">
  <div class="container px-5 py-12 mx-auto">
    <div class="flex flex-col text-center w-full mb-8">
      <h1 class="sm:text-3xl text-2xl font-medium title-font mb-2 text-gray-900">SHOP登録</h1>
    </div>

    <x-input-error class="mb-4 text-center" :messages="$errors->all()"/>
    <form method="post" action="{{route('admin.owners.store')}}">
        @csrf
    <div class="lg:w-1/2 md:w-2/3 mx-auto">
        <div class="-m-2">
            <div class="mx-auto p-2 w-1/2">
                    <div class="relative">
                        <label for="name" class="leading-7 text-sm text-gray-600">Shop名</label>
                        <input type="text" id="name" name="name" value="{{ old('name')}}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                    </div>
                </div>
            <div class="mx-auto p-2 w-1/2">
                <div class="relative">
                    <label for="email" class="leading-7 text-sm text-gray-600">メールアドレス</label>
                    <input type="email" id="email" name="email" value="{{ old('email')}}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
            </div>
            <div class="p-2 w-1/2 mx-auto">
                <div class="relative">
                    <label for="password" class="leading-7 text-sm text-gray-600">パスワード</label>
                    <input type="password" id="password" name="password" value="{{ old('password')}}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
            </div>
            <div class="p-2 w-1/2 mx-auto">
                <div class="relative">
                    <label for="password_confirmation" class="leading-7 text-sm text-gray-600">パスワード確認</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" value="{{ old('password_confirmation')}}" required class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                </div>
            </div>            

        <div class="p-2 flex justify-around w-full">
          <button type="button" onclick="location.href='{{ route('admin.owners.index')}}'" class=" text-white bg-gray-300 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg">戻る</button>
          <button type="submit" class=" text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">登録する</button>
        </div>

      </div>
    </form>
    </div>
  </div>
</section>        
        


        </div>
            </div>
        </div>
    </div>
</x-app-layout>
