import React from 'react'
import {
    Create,
    SimpleForm,
    TextInput,
    NumberInput,
    BooleanInput
} from 'react-admin'
import { JsonInput } from "react-admin-json-view";

function CreateRender({ component: Component = Create, config, ...props }) {
    return (
        <Component {...props}>
            <SimpleForm>
                {Object.entries(config).map(([key, v]) => {
                    const { type, ...props } = v
                    let ItemComponent = null
                    switch (type) {
                        case 'json':
                            ItemComponent = JsonInput
                            break
                        case 'bool':
                            ItemComponent = BooleanInput
                            break
                        case 'number':
                            ItemComponent = NumberInput
                            break
                        case 'text':
                        default:
                            ItemComponent = TextInput
                    }
                    return (
                        <ItemComponent key={key} source={key} {...props} />
                    )
                })}
            </SimpleForm>
        </Component>
    )
}

export default CreateRender