import { useForm } from "react-hook-form";
import * as yup from 'yup';
import { yupResolver } from "@hookform/resolvers/yup";
import { useMutation } from '@tanstack/react-query';
import ReCAPTCHA from "react-google-recaptcha";
import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Post from "api/post";
import Alert from "components/ui/Alert";
import { passValidation } from "utils/yupValidations";

const Registration = ({ setAction }) => {
    const [captcha, setCaptcha] = useState(null);

    const { data, mutate, isLoading, isError, error } = useMutation(['registration'], inputs => Post('/auth/registration', inputs));
    const navigate = useNavigate();

    const schema = yup.object().shape({
        name: yup.string().min(3).max(30)
            .matches(/^[0-9aA-zZ\s]+$/, "Nick can't contain any special characters")
            .required(),
            ...passValidation
    });

    const { register, handleSubmit, formState: { errors } } = useForm({
        resolver: yupResolver(schema)
    });

    const onSubmit = inputs => {
        inputs['g-recaptcha-response'] = captcha;

        mutate(inputs);
    }

    return (
        <div className="auth-card">
            <h5 className="mb-6 text-lg"> Registration </h5>

            { isError && <Alert text={error.response.data.message} /> }
            { data && <Alert stat={true} text={data.message} />}

            <form onSubmit={handleSubmit(onSubmit)}>
                <div className="mb-4">
                    <label className="block text-sm font-bold mb-1" htmlFor="name"> Username </label>
                    <input
                        type="text"
                        placeholder="Username"
                        id="name" {...register("name")}
                    />
                    <p className="text-xs text-red-600 mt-2">{errors?.name?.message}</p>
                </div>

                <div className="mb-4">
                    <label className="block text-sm font-bold mb-1" htmlFor="password"> Password </label>
                    <input
                        type="Password"
                        placeholder="Password"
                        id="password" {...register("password")}
                    />
                    <p className="text-xs text-red-600 mt-2">{errors?.password?.message}</p>
                </div>

                <div className="mb-4">
                    <label className="block text-sm font-bold mb-1" htmlFor="password_confirmation"> Repeat password </label>
                    <input
                        type="Password"
                        placeholder="Repeat assword"
                        id="password_confirmation"
                        {...register("password_confirmation")}
                    />
                    <p className="text-xs text-red-600 mt-2">{errors['password_confirmation']?.message}</p>
                </div>
                <div className="mb-4 w-50 recaptcha">
                    <ReCAPTCHA sitekey="6LcqlrYoAAAAAJTzYv8vmwG7RXepK2F1IOppKZh3" onChange={str => setCaptcha(str)} />
                </div>

                <button>
                    {isLoading && <span className="loading loading-spinner text-white w-3 mr-1"></span>}
                    Registration
                </button>
                <div className="text-gray-500 font-thin">Have an account? <span className="text-blue-800  cursor-pointer hover-opacity" onClick={() => setAction(0)}>Login</span> </div>
            </form>

        </div>
    );
}

export default Registration;