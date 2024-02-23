import Modal from "components/ui/Modal";
import Delete from "api/delete";
import { useMutation } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import { useEffect } from "react";

const LogOut = () => {
    const {
        data: lgData,
        mutate: lgMutate,
        isLoading: lgIsLoading,
        status: lgStatus
    } = useMutation(['logOut'], () => Delete('auth/logout'));

    const {
        data: lgdData,
        mutate: lgdMutate,
        isLoading: lgdIsLoading,
        status: lgdStatus
    } = useMutation(['logOutAllDevices'], () => Delete('auth/logoutFromAllDevices'));

    const navigate = useNavigate();

    useEffect(() => {
        if(lgStatus === 'success' || lgdStatus === 'success') {
            navigate('/');
        }
    }, [lgData, lgdData]);

    return (
        <Modal id="log-out" heading="Log out">
            <a
                className={`text-slate-600 border border-slate-300 rounded-md p-2 block ${!lgIsLoading && lgData?.stat ? 'border-green-300' : 'border-slate-300'}`}
                onClick={lgMutate}>
                Log out from this browser
                {lgIsLoading && <span className="loading loading-circle text-primary w-3 ml-2"></span>}
            </a>

            <a
                className={`text-slate-600 border border-slate-300 rounded-md p-2 block mt-3 ${!lgdIsLoading && lgdData?.stat ? 'border-green-300' : 'border-slate-300'}`}
                onClick={lgdMutate}>
                Log out from all devices
                {lgdIsLoading && <span className="loading loading-circle text-primary w-3 ml-2"></span>}
            </a>
        </Modal>
    );
}

export default LogOut;