<header class="bg-white dark:bg-gray-800 shadow fixed left-0 md:left-96 right-0 md:z-40 z-0 ">
    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-4">
        <div class="flex items-center">
            <button 
                data-drawer-target="drawer-navigation"
                data-drawer-show="drawer-navigation"
                data-drawer-backdrop="false"
                data-drawer-body-scrolling="true"
                aria-controls="drawer-navigation"
                class="p-2 mr-2 text-gray-600 rounded-lg cursor-pointer md:hidden hover:text-gray-900 hover:bg-gray-100 focus:bg-gray-100 dark:focus:bg-gray-700 focus:ring-2 focus:ring-gray-100 dark:focus:ring-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                >
                <svg
                    aria-hidden="true"
                    class="w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                    fill-rule="evenodd"
                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                    clip-rule="evenodd"
                    ></path>
                </svg>
                <svg
                    aria-hidden="true"
                    class="hidden w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                    fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                    ></path>
                </svg>
                <span class="sr-only">Toggle sidebar</span>
            </button>

            <div class="flex-shrink-0">
                <div class="relative">
                    {{-- <img class="w-10 h-10 rounded-full" src="https://avatars.githubusercontent.com/u/167683279?v=4" alt=""> --}}
                    <div class="w-8 h-8 rounded-full {{ $userModel->avatar['color'] }}  flex items-center justify-center">
                        <span class="text-xs font-medium text-white">
                            {{ $userModel->avatar['initials'] }}
                        </span>
                    </div>
                    <span class="bottom-0 left-7 absolute  w-3.5 h-3.5 {{ $isOnline ? 'animate-pulse bg-green-400' : 'bg-red-400' }} border-2 border-white dark:border-gray-800 rounded-full"></span>
                    {{-- <livewire:chat-user-online :senderId="$senderId" :authId="$authId" :wire:key="'chat-online-'.$senderId.$authId" /> --}}
                </div>
                {{-- <img class="w-8 h-8 rounded-full" src="https://avatars.githubusercontent.com/u/167683279?v=4" alt="Neil image"> --}}
            </div>
            <div class="flex-1 min-w-0 ms-4">
                <button type="button" data-drawer-target="drawer-profile" data-drawer-show="drawer-profile" data-drawer-placement="right" data-drawer-backdrop="false" data-drawer-body-scrolling="true" aria-controls="drawer-profile" class="text-sm font-medium text-gray-900 truncate dark:text-white">
                    {{ $userModel->name }} 
                </button>
               
                @if ($isTyping)
                     <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                        typing...
                    </p>
                @else
                    <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                        {{ $userModel->email }}
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div id="drawer-profile" class="fixed top-0 right-0 z-40 w-screen md:w-96 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white dark:bg-gray-800 border-s border-slate-600" tabindex="-1" aria-labelledby="drawer-right-label">
        <h5 id="drawer-right-label" class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
            <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            Contact info
        </h5>
        <button type="button" data-drawer-hide="drawer-profile" aria-controls="drawer-profile" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white" >
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            <span class="sr-only">Close menu</span>
        </button>
        <div class="flex flex-col items-center pb-10">
            {{-- <img class="w-24 h-24 mb-3 rounded-full shadow-lg" src="/docs/images/people/profile-picture-3.jpg" alt="Bonnie image"/> --}}
            <div class="w-24 h-24 mb-3 shadow-lg rounded-full {{ $userModel->avatar['color'] }}  flex items-center justify-center">
                <span class="text-xl font-medium text-white">
                    {{ $userModel->avatar['initials'] }}
                </span>
            </div>
            <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $userModel->name }}</h5>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $userModel->email }}</span>
        </div>

        <h6 class="mb-3 text-sm text-gray-900 md:text-md dark:text-white">
            About
        </h6>
        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Connect with one of our available wallet providers or create a new one.</p>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

        
       

        <span class="flex items-center gap-2 mb-3">
            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 16 5-7 6 6.5m6.5 2.5L16 13l-4.286 6M14 10h.01M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
            </svg>
            <h6 class="text-sm text-gray-900 md:text-md dark:text-white">Media, links and docs</h6>
        </span>
        <div class="grid grid-cols-4 gap-4">
            <div>
                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-1.jpg" alt="">
            </div>
            <div>
                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-2.jpg" alt="">
            </div>
            <div>
                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-3.jpg" alt="">
            </div>
            <div>
                <img class="h-auto max-w-full rounded-lg" src="https://flowbite.s3.amazonaws.com/docs/gallery/square/image-4.jpg" alt="">
            </div>
        </div>

        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

    </div>
</header>