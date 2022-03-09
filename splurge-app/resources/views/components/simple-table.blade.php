<!-- This example requires Tailwind CSS v2.0+ -->
@props(['columns'])
<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-splarge-200 sm:rounded-lg">
          <table class="min-w-full divide-y divide-splarge-200">
            <thead class="bg-splarge-100">
              <tr>
                  @foreach ($columns as $column)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{$column}}
                    </th>      
                  @endforeach
              </tr>
            </thead>
            @if (isset($footer))
            <tfoot class="bg-splarge-100">
                <tr>
                    <td colspan="{{ count($columns)}}">
                        {{ $footer }}
                    </td>
                </tr>
            </tfoot>
            @endif
            <tbody class="bg-white divide-y divide-splarge-200">
                {{ $slot }}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  