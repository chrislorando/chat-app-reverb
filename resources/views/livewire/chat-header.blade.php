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
                <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                    {{ $userModel->name }} 
                </p>
               
                @if ($isTyping)
                     <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                        {{-- {{ $userModel->email }} --}}
                        typing...
                    </p>
                @endif
            </div>
        </div>
    </div>
</header>