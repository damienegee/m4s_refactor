import "./Login.css";
import PersonRoundedIcon from '@mui/icons-material/PersonRounded';
import WindowRoundedIcon from '@mui/icons-material/WindowRounded';
import GoogleIcon from '@mui/icons-material/Google';
const logo = require("./logo.png");

const Login = () => {
    return (
        <div className="container">
            <img src={logo} alt="" className="logo" />
            <div className="loginBox">
                <div className="profile">
                    <PersonRoundedIcon fontSize="inherit" />
                </div>
                <div className="buttons">
                    <button className="btn">
                        <span className="btnFlex">
                            <WindowRoundedIcon fontSize="large" className="serviceIcon" />
                            <span className="serviceName">Microsoft</span>
                        </span>
                    </button>
                    <button className="btn">
                        <span className="btnFlex">
                            <GoogleIcon fontSize="large" className="serviceIcon" />
                            <span className="serviceName">Google</span>
                        </span>
                    </button>
                </div>

            </div>
        </div>
    )
}

export default Login