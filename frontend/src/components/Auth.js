import { useQuery } from "@tanstack/react-query";
import { useNavigate } from "react-router-dom";
import { useEffect } from "react";
import Get from "api/get";
import UserContext from "contexts/UserContext";

const Auth = ({ children, checkType }) => {
    const navigate = useNavigate();

    const { data, refetch, status } = useQuery(['checkAuth'], () => Get('/auth/user'));

    const authenticated = () => status === 'success';
    const unauthenticated = () => status === 'error';

    useEffect(() => {
        if (unauthenticated() && checkType) {
            navigate('/');
        }
    
        if (authenticated() && !checkType) {
            navigate('/mail/inbox');
        }
    }, [data, status]);

    return (
        <>
        {authenticated()}
            {(authenticated() && checkType) &&
                <UserContext.Provider value={{ ...data, email: `${data.name}@nebulasend.com`, refetch }}>
                    {children}
                </UserContext.Provider>
            }
            {(unauthenticated() && !checkType) && children}
        </>
    );
}

export default Auth;