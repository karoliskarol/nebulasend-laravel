import { useMutation } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";
import Put from "api/put";
import Alert from "components/ui/Alert";
import { useContext } from "react";
import UserContext from "contexts/UserContext";

const ChangeRecipient = () => {
    const userData = useContext(UserContext);
    const { data, isError, mutate, error, isLoading } = useMutation(['updateRecipient'], inputs => Put('/settings/updateRecipient/', inputs));

    const schema = yup.object().shape({
        recipient_name: yup.string().min(3).max(50).trim()
            .matches(/^[0-9aA-zZ\s]+$/, "Recipient name can't contain any special characters")
            .required()
    });

    const { register, handleSubmit, formState: { errors } } = useForm({
        resolver: yupResolver(schema),
        defaultValues: { recipient_name: userData['recipient_name'] }
    });

    return (
        <form onSubmit={handleSubmit(mutate)}>
            <h3 className="text-lg font-bold">Change recipient name</h3>

            { isError && <Alert text={error.response.data.message} /> }
            { data && <Alert stat={true} text={data.message} />}

            <div className="mt-0 mb-4">
                <label htmlFor="recipient-name">Recipient name</label>
                <input type="text" placeholder="Current password" id="recipient_name" {...register("recipient_name")} />
                <p className="text-xs text-red-600 mt-2">{errors?.recipient_name?.message}</p>
            </div>
            <button className="mb-2">
                {isLoading && <span className="loading loading-circle text-white w-3 mr-1"></span>}
                Continue
            </button>
        </form>
    );
}

export default ChangeRecipient;