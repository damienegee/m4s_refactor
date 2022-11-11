import styles from "./Login.module.css";
import PersonRoundedIcon from '@mui/icons-material/PersonRounded';
import WindowRoundedIcon from '@mui/icons-material/WindowRounded';
import GoogleIcon from '@mui/icons-material/Google';
import {useNavigate} from "react-router-dom";
const logo = require("./logo.png");


const Login = () => {
    const navigate = useNavigate();
    return (
        <div className={styles.container}>
            <img src={logo} alt="" className={styles.logo} />
            <div className={styles.loginBox}>
                <div className={styles.profile}>
                    <PersonRoundedIcon fontSize="inherit" />
                </div>
                <div className={styles.buttons}>
                    <button className={styles.btn} onClick={() => navigate("/home")}>
                        <span className={styles.btnFlex}>
                            <WindowRoundedIcon fontSize="large" className={styles.serviceIcon} />
                            <span className={styles.serviceName}>Microsoft</span>
                        </span>
                    </button>
                    <button className={styles.btn} onClick={() => navigate("/home")}>
                        <span className={styles.btnFlex}>
                            <GoogleIcon fontSize="large" className={styles.serviceIcon} />
                            <span className={styles.serviceName}>Google</span>
                        </span>
                    </button>
                </div>

            </div>
        </div>
    )
}

export default Login