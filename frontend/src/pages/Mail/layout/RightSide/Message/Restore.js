import { InboxArrowDownIcon } from "@heroicons/react/24/solid";
import { useContext } from "react";
import MessageContext from "contexts/MessageContext";
import { useMutation } from "@tanstack/react-query";
import Put from "api/put";

const Restore = () => {
    const { message, setMessage, setCount, qKey } = useContext(MessageContext);

    const { data, mutate } = useMutation(['restoreEmailMessage'], id => Put(`/emails-messages/${id}/restore`).then(() => {
        message.trash = 0;
        setMessage({ ...message });

        setCount(prev => prev - 1);
    }));

    return (qKey === 'trashed' &&
        <InboxArrowDownIcon
            className="w-5 h-5 sm:w-4 sm:h-4 text-blue-800 cursor-pointer"
            onClick={() => mutate(message.id)}
        />
    );
}

export default Restore;