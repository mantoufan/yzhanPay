import React from 'react'
import {
    List,
    Datagrid,
    TextField,
    BooleanField,
    NumberField,
    EditButton
} from 'react-admin'
import { JsonField } from "react-admin-json-view";

function ListRender({ component: Component = List, config, ...props }) {
    return (
        <Component {...props}>
            <Datagrid>
                {Object.entries(config).map(([key, v]) => {
                    const { type, maxWidth, ...props } = v
                    if (maxWidth) {
                        props.style = Object.assign(props.style || {}, {
                            "display": "inline-block",
                            "maxWidth": maxWidth,
                            "whiteSpace": "nowrap",
                            "overflow": "hidden",
                            "textOverflow": "ellipsis"
                        })
                    }  
                    let ItemComponent = null
                    switch (type) {
                        case 'json':
                            ItemComponent = JsonField
                            break
                        case 'bool':
                            ItemComponent = BooleanField
                            break
                        case 'number':
                            ItemComponent = NumberField
                            break
                        case 'text':
                        default:
                            ItemComponent = TextField
                    }
                    return (
                        <ItemComponent key={key} source={key} {...props} />
                    )
                })}
                <EditButton />
            </Datagrid>
        </Component>
    )
}

export default ListRender