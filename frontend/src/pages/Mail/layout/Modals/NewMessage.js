import { useForm } from "react-hook-form";
import * as yup from "yup";
import { yupResolver } from "@hookform/resolvers/yup";
import { useMutation } from "@tanstack/react-query";
import { useEffect } from "react";
import Post from "api/post";
import Modal from "components/ui/Modal";
import Alert from "components/ui/Alert";

const NewMessage = () => {
    const { data, mutate, isLoading, isError, error } = useMutation(['newMessage'], inputs => Post('/emails-messages', inputs));

    const schema = yup.object().shape({
        'sent_to': yup.string().email('Email must be valid.').required('Email is required field.'),
        subject: yup.string().min(2).max(300).required(),
        message: yup.string().required()
    });

    const { register, handleSubmit, reset, formState: { errors } } = useForm({ resolver: yupResolver(schema) });

    useEffect(() => {
        if(data) reset();
    }, [data]);

    return (
        <Modal id="new-message" heading="New message">
            { isError && <Alert text={error.response.data.message} /> }
            { data && <Alert stat={true} text={data.message} />}

            <form onSubmit={handleSubmit(mutate)}>
                <div className="mt-0 mb-4">
                    <label id="sent_to">To</label>
                    <input type="text" placeholder="To" id="sent_to" {...register("sent_to")} />
                    <span className="text-xs text-red-600 mt-2">{errors?.sent_to?.message}</span>
                </div>
                <div className="my-4">
                    <label id="subject">Subject</label>
                    <input type="text" placeholder="Subject" id="subject" {...register("subject")} />
                    <span className="text-xs text-red-600 mt-2">{errors?.subject?.message}</span>
                </div>
                <div className="my-4">
                    <label id="message">Message</label>
                    <textarea placeholder="Message" id="Message" {...register("message")}></textarea>
                    <span className="text-xs text-red-600 mt-2">{errors?.message?.message}</span>
                </div>
                <button className="mb-2">
                    {isLoading && <span className="loading loading-circle text-white w-3 mr-1"></span>}
                    Send
                </button>
            </form>
        </Modal>
    );
}

export default NewMessage;