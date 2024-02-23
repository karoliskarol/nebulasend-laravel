import * as yup from 'yup';

export const passValidation = {
    password: yup.string().min(8).max(30)
        .matches(/[0-9]/, 'Need atleast one number')
        .matches(/[A-Z]/, 'Need atleast one uppercase letter')
        .matches(/[^a-zA-Z0-9]/, 'Need atleast one special character')
        .required(),

    'password_confirmation': yup.string().oneOf([yup.ref("password"), null], "Passwords don't match").required()
}