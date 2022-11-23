import styles from "./Header.module.css";
import LanguageRoundedIcon from '@mui/icons-material/LanguageRounded';
import SearchRoundedIcon from '@mui/icons-material/SearchRounded';
import CreateRoundedIcon from '@mui/icons-material/CreateRounded';
import CloseRoundedIcon from '@mui/icons-material/CloseRounded';
import MenuRoundedIcon from '@mui/icons-material/MenuRounded';
import { useViewport } from "../../hooks/viewport";
import { useEffect, MouseEventHandler } from "react";


interface HeaderProps {
    title: string,
    editState?:boolean,
    setEditState?:(editState:boolean) => void
}
const Header = ({ title,editState,setEditState }: HeaderProps) => {

    const { width } = useViewport();
    const handleOpen: MouseEventHandler<HTMLLIElement> = () => {
        const sidebar = document.getElementById("sidebar");
        const list = document.getElementById("list");
        const close = document.getElementById("navClose");
        const editBtn = document.getElementById("editBtn");
        if (sidebar && list && close) {
            sidebar.style.width = "30%";
            list.style.display = "flex";
            close.style.display = "block"
            if(title.toLowerCase().includes("dashboard") && editBtn){
                editBtn.style.display = "none";
            }
        }
    }
    const handleClose: MouseEventHandler<HTMLLIElement> = (event) => {
        event.currentTarget.style.display = "none";
        const sidebar = document.getElementById("sidebar");
        const list = document.getElementById("list");
        const editBtn = document.getElementById("editBtn");
        if (sidebar && list) {
            sidebar.style.width = "0px";
            list.style.display = "none";
            if(title.toLowerCase().includes("dashboard") && editBtn){
                editBtn.style.display = "block";
            }
        }
    }
    const editHandle : MouseEventHandler<HTMLLIElement> = () => {
        if (setEditState) setEditState(!editState);
    }
    useEffect(() => {
        const sidebar = document.getElementById("sidebar");
        const list = document.getElementById("list");
        const close = document.getElementById("navClose");
        const editBtn = document.getElementById("editBtn");
        if (width > 720 && list && close && sidebar) {
            sidebar.style.width = "25%";
            list.style.display = "flex";
            close.style.display = "none";
            if(title.toLowerCase().includes("dashboard") && editBtn){
                editBtn.style.display = "block";
            }
        } else {
            if (sidebar && list) {
                list.style.display="none";
                sidebar.style.width = "0px";
            }

        }
    })
    return (
        <div className={styles.header}>
            <ul className={styles.wrapper}>
                {width < 720 && <li className={`${styles.menu} ${styles.item}`} onClick={handleOpen}>
                    <MenuRoundedIcon fontSize="inherit" />
                </li>}
                <li className={`${styles.language} ${styles.item}`}>
                    <LanguageRoundedIcon fontSize="inherit" />
                </li>
                <li className={styles.item}>
                    <SearchRoundedIcon fontSize="inherit" />
                </li>
                <li className={` ${styles.title}`}>
                    <h2 >{title}</h2>
                </li>
                <li id="navClose" className={`${styles.item} ${styles.right} ${styles.close}`} onClick={handleClose}>
                    <CloseRoundedIcon fontSize="inherit" />
                </li>
                {title.toLowerCase().includes("dashboard") && <li id="editBtn" className={`${styles.right}`} onClick={editHandle}>
                    <button className={`${styles.editBtn} ${styles.item}`}>Edit widgets <CreateRoundedIcon fontSize="inherit" /></button>
                </li>}
            </ul>

        </div>
    )
}

export default Header;
