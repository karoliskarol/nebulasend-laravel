import Modal from "components/ui/Modal";
import { useEffect, useRef } from "react";
import addNewCookie from "utils/addCookie";

const WebsiteMessageModal = () => {
    const labelRef = useRef(null);

    useEffect(() => {
        labelRef.current.addEventListener('click', null);
        labelRef.current.click();

        addNewCookie('viewedMessage', '1', 30);
    }, []);

    return (
        <>
            <label htmlFor="website-message-modal" className="btn invisible absolute h-0 w-0" ref={labelRef}></label>

            <Modal id="website-message-modal" heading="Hey">
                Our website is currently under development. We are diligently working on it, ensuring that it meets the highest standards. At this stage, it’s primarily for testing purposes. Rest assured, we’ll keep you informed of any exciting developments as we progress. Thank you for your patience!
            </Modal>
        </>
    );
}

export default WebsiteMessageModal;