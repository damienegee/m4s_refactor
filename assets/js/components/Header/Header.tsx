import "./Header.css";
import LanguageRoundedIcon from '@mui/icons-material/LanguageRounded';
import SearchRoundedIcon from '@mui/icons-material/SearchRounded';
import CreateRoundedIcon from '@mui/icons-material/CreateRounded';

const Header = () => {
    return (
        <div className="header">
            <div className="wrapper">
                <LanguageRoundedIcon className="language item" />
                <SearchRoundedIcon className="item" />
                <h2 className ="title item">My Dashboard</h2>
                <button    className="editBtn item">Edit widgets <CreateRoundedIcon/></button>

            </div>

        </div>
    )
}

export default Header