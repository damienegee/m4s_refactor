import styles from "./List.module.css";
import { MouseEventHandler, ReactElement } from "react";
import InsertDriveFileRoundedIcon from '@mui/icons-material/InsertDriveFileRounded';
import EmailRoundedIcon from '@mui/icons-material/EmailRounded';
import CalendarMonthRoundedIcon from '@mui/icons-material/CalendarMonthRounded';
import WidgetBox from "../WidgetBox/WidgetBox";

interface ListProps{
    type:string,
    editState:boolean,
    name:string,
    handleRemove : MouseEventHandler<HTMLSpanElement>
}
interface Content{
    title:string,
    icon:ReactElement | null,
    list:string[]
}
const List = ({type,editState,name,handleRemove}:ListProps) => {
    let content : Content ={
        title: "",
        icon: null,
        list:[]
    }
    switch(type){
        case "images":
            content ={
                title:"Last images",
                icon:<InsertDriveFileRoundedIcon fontSize="inherit"/>,
                list : [
                    "image1",
                    "image2",
                    "image3",
                    "image4",
                    "image5"
                ]
            }
            break;
        case "mails":
            content ={
                title:"Last mails",
                icon:<EmailRoundedIcon fontSize="inherit"/>,
                list : [
                    "mail1",
                    "mail2",
                    "mail3",
                    "mail4",
                    "mail5"
                ]
            }
            break;
        case "incidents":
            content ={
                title:"Last incidents",
                icon:<CalendarMonthRoundedIcon fontSize="inherit"/>,
                list : [
                    "incident1",
                    "incident2",
                    "incident3",
                    "incident4",
                    "incident5"
                ]
            }
            break;
    }
  return (
    <WidgetBox editState={editState} height={225} handleRemove={handleRemove} name={name}>
        <div className={styles.top}>
            <h3 className={styles.title}>{content.title}</h3>
        </div>
        <div className={styles.body}>
            <ul className={styles.list}>
                {content.list.map((item) => 
                     <li key={item} className={styles.listItem}>{content.icon} <span className={styles.itemText}>{item}</span></li>
                )}
            </ul>
        </div>
    </WidgetBox>
  )
}

export default List