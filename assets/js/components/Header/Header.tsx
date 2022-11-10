import styles from "./Header.module.css";
import LanguageRoundedIcon from '@mui/icons-material/LanguageRounded';
import SearchRoundedIcon from '@mui/icons-material/SearchRounded';
import CreateRoundedIcon from '@mui/icons-material/CreateRounded';

interface HeaderProps {
    title:string
}
const Header = ({title}:HeaderProps) => {
    return (
        <div className={styles.header}>
            <div className={styles.wrapper}>
                <LanguageRoundedIcon className={`${styles.language} ${styles.item}`} />
                <SearchRoundedIcon className={styles.item}/>
                <h2 className ={`${styles.item} ${styles.title}`}>{title}</h2>
                {title.toLowerCase().includes("dashboard") && <button    className={`${styles.editBtn} ${styles.item}`}>Edit widgets <CreateRoundedIcon/></button>}

            </div>

        </div>
    )
}

export default Header;
