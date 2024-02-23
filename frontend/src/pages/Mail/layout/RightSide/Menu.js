import { ArrowPathIcon, ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/solid";

const Menu = ({ refetch, isFetching, handlePagination, data }) => {
    const max = 20;

    return (
        <div className="flex fixed w-full bg-light items-center shadow-md h-12 px-4 z-10">
            {
                isFetching ?
                    <span className="loading loading-circle text-blue-800 w-5 h-5 mr-1"></span>
                    :
                    <ArrowPathIcon className="w-5 h-5 text-blue-800 font-bold cursor-pointer" onClick={refetch} />
            }

            { data?.meta?.total > max &&
                <div className="flex mx-3 items-center">
                    <ChevronLeftIcon className="w-5 h-5 text-blue-800 cursor-pointer" onClick={() => handlePagination(false)} />
                    <div className="text-sm"> Showing {data.meta.from} - {data.meta.to} / {data.meta.total} records </div>
                    <ChevronRightIcon className="w-5 h-5 text-blue-800 cursor-pointer" onClick={() => handlePagination(true, data.meta.last_page)} />
                </div>
            }
        </div>
    );
}

export default Menu;