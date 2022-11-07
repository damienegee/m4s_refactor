import "./Sidebar.css";
const logo = require("./logo.png");
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

const Sidebar = () => {
  const handleShow: MouseEventHandler<HTMLLIElement> = (event) => {
    const subList = document.getElementById("subList");
    const close = document.getElementById("close");
    event.currentTarget.classList.add("none");
    subList?.classList.add("show")
    close?.classList.remove("none");
  }
  const handleHide: MouseEventHandler<HTMLLIElement> = (event) => {
    const subList = document.getElementById("subList");
    const more = document.getElementById("more");
    subList?.classList.remove("show")
    more?.classList.remove("none");
    event.currentTarget.classList.add("none");
  }
  return (
    <div className="sidebar">
      <img className="logo" src={logo} alt="" />
      <ul className="list">
        <li className="listItem">
          <DashboardRoundedIcon />
          <span >Dashboard</span>
        </li>
        <li className="listItem">
          <StoreRoundedIcon />
          <span >Webshop</span>
        </li>
        <li className="listItem">
          <LocalLibraryRoundedIcon />
          <span >Leermiddel</span>
        </li>
        <li id="close" className="listItem none close" onClick={handleHide}>
          <CloseRoundedIcon fontSize="medium" />
          Close
        </li>
        <li id="more" className="listItem" onClick={handleShow}>
          <MoreHorizRoundedIcon />
          <span >Overige</span>
        </li>
        <div id="subList" className="subList">
          <li className="listItem">
            <Inventory2RoundedIcon />
            <span >Inventaris</span>
          </li>
          <li className="listItem">
            <CalendarMonthRoundedIcon />
            <span >Incident</span>
          </li>
          <li className="listItem">
            <LaptopChromebookRoundedIcon />
            <span >Toestellen</span>
          </li>
          <li className="listItem">
            <PeopleAltRoundedIcon />
            <span >Gebruikers</span>
          </li>
        </div>

        <li className="listItem logout">
          <LogoutRoundedIcon />
          <span >Log out</span>
        </li>
      </ul>

    </div>
  )
}

export default Sidebar