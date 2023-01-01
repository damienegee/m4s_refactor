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
import styles from "./Sidebar.module.css";
import { useNavigate } from "react-router-dom";
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
    <div id='sidebar' className={styles.sidebar}>
      <img className={styles.logo} src={logo} alt="" />
      <ul id='list' className={styles.list}>
        <li className={styles.listItem} onClick={() => navigate("/home")}>
          <div className={styles.itemIcon}>
            <DashboardRoundedIcon fontSize='inherit' />
          </div>
          <span className={styles.itemTitel} >Dashboard</span>
        </li>
        <li className={styles.listItem}>
          <div className={styles.itemIcon}>
            <StoreRoundedIcon fontSize='inherit' />
          </div>
          <span className={styles.itemTitel} >Webshop</span>
        </li>
        <li className={styles.listItem}>
          <div className={styles.itemIcon}>
            <LocalLibraryRoundedIcon  fontSize='inherit'/>
          </div>
          <span className={styles.itemTitel}>Leermiddel</span>
        </li>
        <li id="close" className={`${styles.listItem} ${styles.none} ${styles.close}`} onClick={handleHide}>
          <div className={styles.itemIcon}>
            <CloseRoundedIcon fontSize='inherit' />
          </div>
          <span className={styles.itemTitel}>Close</span>
        </li>
        <li id="more" className={styles.listItem} onClick={handleShow}>
          <div className={styles.itemIcon}>
            <MoreHorizRoundedIcon fontSize='inherit' />
          </div>
          <span className={styles.itemTitel}>Overige</span>
        </li>
        <div id="subList" className={styles.subList}>
          <li className={styles.listItem} onClick={() => navigate("/inventaris")}>
            <div className={styles.itemIcon}>
              <Inventory2RoundedIcon  fontSize='inherit'/>
            </div>
            <span className={styles.itemTitel} >Inventaris</span>
          </li>
          <li className={styles.listItem} onClick={() => navigate("/incidents")}>
            <div className={styles.itemIcon}>
              <CalendarMonthRoundedIcon fontSize='inherit' />
            </div>
            <span  className={styles.itemTitel}>Incident</span>
          </li>
          <li className={styles.listItem} onClick={() => navigate("/devices")}>
            <div className={styles.itemIcon}>
              <LaptopChromebookRoundedIcon fontSize='inherit' />
            </div>
            <span className={styles.itemTitel} >Toestellen</span>
          </li>
          <li className={styles.listItem} onClick={() => navigate("/users")}>
            <div className={styles.itemIcon}>
              <PeopleAltRoundedIcon fontSize='inherit'/>
            </div>
            <span  className={styles.itemTitel}>Gebruikers</span>
          </li>
        </div>

        <li className={`${styles.listItem} ${styles.logout}`} onClick={() => navigate("/")}>
          <div className={styles.itemIcon}>
            <LogoutRoundedIcon  fontSize='inherit'/>
          </div>
          <span className={styles.itemTitel} >Log out</span>
        </li>
      </ul>

    </div>
  )
}

export default Sidebar