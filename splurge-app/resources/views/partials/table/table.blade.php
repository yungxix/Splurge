<!-- This example requires Tailwind CSS v2.0+ -->
<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                  @foreach ($table->getColumns() as $column)
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        {{ $column->renderHead($loop) }}
                    </th>      
                  @endforeach
              </tr>
            </thead>
            @if ($table->isPaginated())
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="{{ count($table->getColumns())}}">
                        <div class="flex flex-row items-center justify-end">
                            {{ $table->getData()->links() }}
                        </div>
                    </td>
                </tr>
            </tfoot>
            @endif
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($table->getData() as $model)
                    @php
                        $row_loop = $loop;
                    @endphp
                    <tr class="align-top">
                        @foreach ($table->getColumns() as $column)
                            @if ('th' === $column->getTag())
                                <th scope="row" class="px-6 py-4 whitespace-nowrap {{ $column->getCellClass($row_loop->index, $loop->index) }}">
                                    {{ $column->render($model, $row_loop, $loop)  }}
                                </th>
                            @else
                                <td class="px-6 py-4 whitespace-nowrap  {{ $column->getCellClass($row_loop->index, $loop->index) }}">
                                    {{ $column->render($model, $row_loop, $loop)  }}
                                </td>
                            @endif
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  