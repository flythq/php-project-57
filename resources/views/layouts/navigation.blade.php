<nav x-data="{ open: false }" class="bg-white border-gray-200 py-2.5 shadow-md">
    <div class="flex flex-wrap items-center justify-between max-w-screen-xl px-4 mx-auto">
        <a href="{{ route('home') }}" class="flex items-center">
            <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-black">
                Менеджер задач
            </span>
        </a>

        <div class="flex items-center lg:order-2">
            @auth
                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                    Выход
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;" novalidate>
                    @csrf
                </form>
            @else
                <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Вход
                </a>
                <a href="{{ route('register') }}"
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                    Регистрация
                </a>
            @endauth
        </div>

        <div class="items-center justify-between hidden w-full lg:flex lg:w-auto lg:order-1">
            <ul class="flex flex-col  font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                <li>
                    <a href=""
                       class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0">
                        Задачи
                    </a>
                </li>
                <li>
                    <a href=""
                       class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0">
                        Статусы
                    </a>
                </li>
                <li>
                    <a href=""
                       class="block py-2 pl-3 pr-4 text-gray-700 hover:text-blue-700 lg:p-0">
                        Метки
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
