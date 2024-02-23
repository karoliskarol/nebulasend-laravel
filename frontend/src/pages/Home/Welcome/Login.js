import { useForm } from "react-hook-form";
import * as yup from "yup";
import { yupResolver } from "@hookform/resolvers/yup";
import { useMutation } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import Post from "api/post";
import Alert from "components/ui/Alert";
import { useEffect } from "react";

const Login = ({ setAction }) => {
    const { data, mutate, isLoading, isError, error } = useMutation(['login'], inputs => Post('/auth/login', inputs));
    const navigate = useNavigate();

    const schema = yup.object().shape({
        name: yup.string().required(),
        password: yup.string().required()
    });

    const { register, handleSubmit } = useForm({ resolver: yupResolver(schema) });

    useEffect(() => {
        if(data) window.location.reload();
    }, [data]);

    return (
        <div className="auth-card">
            <h5 className="mb-6 text-lg"> Login </h5>
            
            { isError && <Alert text={error.response.data.message} /> }
            { data && <Alert stat={true} text={data.message} />}

            <form onSubmit={handleSubmit(mutate)}>
                <div className="mb-4">
                    <label className="block text-sm font-bold mb-1" htmlFor="username"> Username </label>
                    <input type="text" placeholder="Nickname" {...register("name")} />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-bold mb-1" htmlFor="password"> Password </label>
                    <input type="Password" placeholder="Password" {...register("password")} />
                </div>
                <button>
                    {isLoading && <span className="loading loading-circle text-white w-3 mr-1"></span>}
                    Login
                </button>
                <div className="text-gray-500 font-thin">
                    Don't have an account? <span className="text-blue-800 cursor-pointer hover-opacity" onClick={() => setAction(1)}>Registration</span>
                </div>
            </form>

        </div>
    );
}

export default Login;