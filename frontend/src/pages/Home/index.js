import Header from './Header';
import Welcome from './Welcome';
import About from './About';
import Statistics from './Statistics';
import Footer from './Footer';
import Contacts from './Contacts';
import Auth from 'components/Auth';
import WebsiteMessageModal from './WebsiteMessageModal';
import getCookie from 'utils/getCookie';

const Home = () => {
    return (
        <Auth checkType={false}>
            <Header />
            <main>
                <Welcome />
                <About />
                <Statistics />
                <Contacts />
                {!(getCookie('viewedMessage') === '1') && <WebsiteMessageModal />}
            </main>
            <Footer />
        </Auth>
    );
}

export default Home;