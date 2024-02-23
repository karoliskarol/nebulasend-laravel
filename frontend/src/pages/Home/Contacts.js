import * as yup from 'yup';
import { yupResolver } from "@hookform/resolvers/yup";
import { useMutation } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import { useForm } from 'react-hook-form';
import Post from 'api/post';
import Alert from 'components/ui/Alert';

const Contacts = () => {
    const { data, mutate, isLoading, isError, error } = useMutation(['contactSupport'], inputs => Post('/contactSupport', inputs));
    const navigate = useNavigate();

    const schema = yup.object().shape({
        recipient: yup.string().min(2).max(100).required(),
        'sent_by': yup.string().email().required(),
        message: yup.string().required()
    });

    const { register, handleSubmit, formState: { errors } } = useForm({
        resolver: yupResolver(schema)
    });

    return (
        <section className="contacts py-16" id="contacts">
            <div className="container m-auto">
                <h2 className="m-auto text-center text-3xl mb-8 font-bold">Contacts</h2>

                <div className="max-w-xs additional-mx mb-4">
                    {isError && <Alert text={error.response.data.message} />}
                    {data && <Alert stat={true} text={data.message} />}
                </div>

                <form onSubmit={handleSubmit(mutate)} className="grid grid-cols-1 md:grid-cols-2 gap-4 additional-mx">
                    <div className="col-span-2 md:col-span-1 mb-4">
                        <label className="block text-sm font-bold mb-1" htmlFor="recipient"> Full name </label>
                        <input type="text" placeholder="Fullname" id="recipient" {...register('recipient')} />
                        <p className="text-xs text-red-600 mt-2">{errors?.name?.message}</p>
                    </div>
                    <div className="col-span-2 md:col-span-1 mb-4">
                        <label className="block text-sm font-bold mb-1" htmlFor="sent_by"> Your email </label>
                        <input type="text" placeholder="Your email" id="sent_by" {...register('sent_by')} />
                        <p className="text-xs text-red-600 mt-2">{errors['sent_by']?.message}</p>
                    </div>
                    <div className="mb-0 col-span-2">
                        <label className="block text-sm font-bold mb-1" htmlFor="message"> Your text </label>
                        <textarea type="text" placeholder="Your text" id="message" {...register('message')}></textarea>
                        <p className="text-xs text-red-600 mt-2">{errors?.message?.message}</p>
                    </div>
                    <div className="mb-4">
                        <button className="max-w-xs float-none md:float-left">
                            {isLoading && <span className="loading loading-spinner text-white w-3 mr-1"></span>}
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </section>
    );
}

export default Contacts;