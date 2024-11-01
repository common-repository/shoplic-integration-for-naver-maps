import {
    DetailedHTMLProps,
    HTMLAttributes,
    PropsWithChildren,
    TdHTMLAttributes,
    ThHTMLAttributes,
} from 'react'
import {joinClassNames} from '../../libs/helper'


type TableProps = HTMLAttributes<HTMLTableElement> & PropsWithChildren
type TdProps = DetailedHTMLProps<TdHTMLAttributes<HTMLTableCellElement>, HTMLTableCellElement> & PropsWithChildren
type ThProps = DetailedHTMLProps<ThHTMLAttributes<HTMLTableCellElement>, HTMLTableCellElement> & PropsWithChildren
type TrProps = HTMLAttributes<HTMLTableRowElement> & PropsWithChildren


function Field(props: TdProps) {
    return <td {...props}>{props.children}</td>
}

function FormTable(props: TableProps) {
    const {
        children,
        className,
    } = props

    return (
        <table
            className={`${joinClassNames('form-table', className)}`}
            {...props}
        >
            <tbody>
                {children}
            </tbody>
        </table>
    )
}

function Header(props: ThProps) {
    return <th scope="row" {...props}>{props.children}</th>
}


function Row(props: TrProps) {
    return <tr {...props}>{props.children}</tr>
}

export {
    Field,
    FormTable,
    Header,
    Row,
}
