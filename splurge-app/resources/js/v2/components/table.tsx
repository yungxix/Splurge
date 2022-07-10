import React, {FC} from "react";

export interface Column<T> {
    text: string;
    headerClass?: string;
    cellClass?: string;
    render: (v: T, options?: any) => any;
    keyOf: (v: T, index: number) => any;
}

export interface TableViewProps<T> {
    columns: Column<T>[];
    caption?: string;
    records: T[];
    children?: any;
    footer?: any;
    className?: string;
}


export function TableView<T>(props: TableViewProps<T>) {
    return <div className="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
    <div className="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
      <div className="shadow overflow-hidden border-b border-splarge-200 sm:rounded-lg">
          
         
        <table className="min-w-full divide-y divide-splarge-200">
          {props.caption && (<caption className="py-4 text-splarge-700 text-lg">{props.caption}</caption>)}
          <thead className="bg-gray-100">
            <tr>
                {
                    props.columns.map((col, i) => (<th key={`table_view_col_${i}`} scope="col"
                     className="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                    {col.text}
                 </th>))
                }
            </tr>
            
          </thead>
          {props.footer && (<tfoot>
            {props.footer}
          </tfoot>)}
          <tbody className="bg-white divide-y divide-splarge-200">
              {
                  props.records.map((record, ri) => (<tr key={`table_view_record_${ri}`}>
                      {
                          props.columns.map((col, idx) => (<td key={col.keyOf(record, idx)} className={col.cellClass || 'px-6 py-4 whitespace-nowrap'}>
                              {col.render(record, {row: ri, index: idx})}
                          </td>))
                      }
                  </tr>))
              }
              {props.children}
          </tbody>
        </table>
      </div>
    </div>
  </div>
}
