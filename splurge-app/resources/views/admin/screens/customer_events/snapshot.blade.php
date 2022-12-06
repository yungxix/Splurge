
        <table class="w-full">
            <tbody>
                @isset($include_name)
                <tr class="align-middle">
                    <th scope="row" colspan="2" class="text=cemter">
                        <h3 class="text-xl font-bold mt-2 mb-4">
                            {{ $customer_event->name }}
                        </h3>
                    </th>
                </tr>    
                @endisset
                <tr class="align-middle">
                    <th scope="row">
                        <svg class="w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                        </svg>
                    </th>
                    <td>
                        {{ $customer_event->safeEventDate()->format('jS F, Y') }}
                        <span class="ml-4 text-gray-800">
                            {{ $customer_event->safeEventDate()->diffForHumans() }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <svg class="w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                          </svg>
                          
                    </th>
                    <td>
                        {{ $customer_event->booking->serviceTier->name }}
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <svg class="w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </th>
                    <td>
                        <strong>
                        {{ $customer_event->booking->customer->first_name }}
                        {{ $customer_event->booking->customer->last_name }}
                        </strong>
                        <p>
                            @foreach (['email' => 'mailto:', 'phone' => 'tel:'] as $attr => $url_prefix )
                                @unless (empty($customer_event->booking->customer[$attr]))
                                    <a class="link mr-4" href="{{ $url_prefix }}{{ $customer_event->booking->customer[$attr] }}">
                                        {{ $customer_event->booking->customer[$attr] }}
                                    </a>
                                @endunless
                            @endforeach
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <svg class="w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                          </svg>
                    </th>
                    <td>
                        <address>
                            @foreach (['line1', 'line2', 'state'] as $attr )
                                @unless (empty($customer_event->booking->location[$attr]))
                                <em class="block">
                                    {{ $customer_event->booking->location[$attr] }}
                                </em>    
                                @endunless
                            @endforeach
                        </address>
                    </td>
                </tr>
            </tbody>

        </table>