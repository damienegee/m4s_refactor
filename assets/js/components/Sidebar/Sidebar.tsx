import DashboardRoundedIcon from '@mui/icons-material/DashboardRounded';
import StoreRoundedIcon from '@mui/icons-material/StoreRounded';
import LocalLibraryRoundedIcon from '@mui/icons-material/LocalLibraryRounded';
import LogoutRoundedIcon from '@mui/icons-material/LogoutRounded';
import MoreHorizRoundedIcon from '@mui/icons-material/MoreHorizRounded';
import CloseRoundedIcon from '@mui/icons-material/CloseRounded';
import PeopleAltRoundedIcon from '@mui/icons-material/PeopleAltRounded';
import LaptopChromebookRoundedIcon from '@mui/icons-material/LaptopChromebookRounded';
import CalendarMonthRoundedIcon from '@mui/icons-material/CalendarMonthRounded';
import Inventory2RoundedIcon from '@mui/icons-material/Inventory2Rounded';
import { MouseEventHandler } from "react";
import styles from  "./Sidebar.module.css";
import {useNavigate} from "react-router-dom";
const logo = require("./logo.png");



const Sidebar = () => {
  const navigate = useNavigate();
  const handleShow: MouseEventHandler<HTMLLIElement> = (event) => {
    const subList = document.getElementById("subList");
    const close = document.getElementById("close");
    event.currentTarget.classList.add(styles.none);
    subList?.classList.add(styles.show)
    close?.classList.remove(styles.none);
  }
  const handleHide: MouseEventHandler<HTMLLIElement> = (event) => {
    const subList = document.getElementById("subList");
    const more = document.getElementById("more");
    subList?.classList.remove(styles.show)
    more?.classList.remove(styles.none);
    event.currentTarget.classList.add(styles.none);
  }
  return (
    <div className={styles.sidebar}>
      <img className={styles.logo} src={logo} alt="" />
      <ul className={styles.list}>
        <li className={styles.listItem} onClick={() => navigate("/home")}>
          <DashboardRoundedIcon />
          <span >Dashboard</span>
        </li>
        <li className={styles.listItem}>
          <StoreRoundedIcon />
          <span >Webshop</span>
        </li>
        <li className={styles.listItem}>
          <LocalLibraryRoundedIcon />
          <span >Leermiddel</span>
        </li>
        <li id="close" className={`${styles.listItem} ${styles.none} ${styles.close}`} onClick={handleHide}>
          <CloseRoundedIcon fontSize="medium" />
          Close
        </li>
        <li id="more" className={styles.listItem} onClick={handleShow}>
          <MoreHorizRoundedIcon />
          <span >Overige</span>
        </li>
        <div id="subList" className={styles.subList}>
          <li className={styles.listItem} onClick={() => navigate("/inventaris")}>
            <Inventory2RoundedIcon />
            <span >Inventaris</span>
          </li>
          <li className={styles.listItem} onClick={() => navigate("/incidents")}>
            <CalendarMonthRoundedIcon />
            <span >Incident</span>
          </li>
          <li className={styles.listItem}>
            <LaptopChromebookRoundedIcon />
            <span >Toestellen</span>
          </li>
          <li className={styles.listItem} onClick={() => navigate("/users")}>
            <PeopleAltRoundedIcon />
            <span >Gebruikers</span>
          </li>
        </div>

        <li className={`${styles.listItem} ${styles.logout}`} onClick={() => navigate("/")}>
          <LogoutRoundedIcon />
          <span >Log out</span>
        </li>
      </ul>

    </div>
  )
}

export default Sidebar