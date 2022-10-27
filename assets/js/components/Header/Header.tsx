import "./Header.css";
import LanguageRoundedIcon from '@mui/icons-material/LanguageRounded';
import SearchRoundedIcon from '@mui/icons-material/SearchRounded';
import CreateRoundedIcon from '@mui/icons-material/CreateRounded';

interface HeaderProps {
    title:string
}
const Header = ({title}:HeaderProps) => {
    return (
        <div className="header">
            <div className="wrapper">
                <LanguageRoundedIcon className="language item" />
                <SearchRoundedIcon className="item" />
                <h2 className ="title item">{title}</h2>
                {title.toLowerCase().includes("dashboard") && <button    className="editBtn item">Edit widgets <CreateRoundedIcon/></button>}

            </div>

        </div>
    )
}

export default Header