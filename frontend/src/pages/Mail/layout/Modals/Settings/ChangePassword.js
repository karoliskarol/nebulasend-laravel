import { useMutation } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import * as yup from "yup";
import { yupResolver } from "@hookform/resolvers/yup";
import Put from "api/put";
import { passValidation } from "utils/yupValidations";
import Alert from "components/ui/Alert";

const ChangePassword = () => {
    const { data, isError, mutate, error, isLoading } = useMutation(['updatePassword'], inputs => Put('/settings/updatePassword/', inputs));

    const schema = yup.object().shape({
        opassword: yup.string().required(),
        ...passValidation
    });

    const { register, handleSubmit, formState: { errors } } = useForm({
        resolver: yupResolver(schema)
    });
 
    return (
        <form onSubmit={handleSubmit(mutate)}>
            <h3 className="text-lg font-bold">Change password</h3>
            
            { isError && <Alert text={error.response.data.message} /> }
            { data && <Alert stat={true} text={data.message} />}

            <div className="mt-0 mb-4">
                <label htmlFor="opassword">Current password</label>
                <input type="password" placeholder="Current password" id="opassword" {...register("opassword")} />
                <p className="text-xs text-red-600 mt-2">{errors?.opassword?.message}</p>
            </div>
            <div className="my-4">
                <label htmlFor="password">New password</label>
                <input type="password" placeholder="New password" id="password" {...register("password")} />
                <p className="text-xs text-red-600 mt-2">{errors?.pass?.message}</p>
            </div>
            <div className="my-4">
                <label htmlFor="password_confirmation">Repeat new password</label>
                <input type="password" placeholder="Repeat new password" id="password_confirmation" {...register("password_confirmation")} />
                <p className="text-xs text-red-600 mt-2">{errors?.password_confirmation?.message}</p>
            </div>
            <button>
                {isLoading && <span className="loading loading-circle text-white w-3 mr-1"></span>}
                Continue
            </button>
        </form>
    );
}

export default ChangePassword;