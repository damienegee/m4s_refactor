import styles from "./Counter.module.css";
import { MouseEventHandler, ReactElement } from "react";
import LaptopChromebookRoundedIcon from '@mui/icons-material/LaptopChromebookRounded';
import CalendarMonthRoundedIcon from '@mui/icons-material/CalendarMonthRounded';
import PeopleAltRoundedIcon from '@mui/icons-material/PeopleAltRounded';
import WidgetBox from "../WidgetBox/WidgetBox";

interface CounterProps{
    type:string,
    editState:boolean,
    name:string,
    handleRemove : MouseEventHandler<HTMLSpanElement>
}
interface Content{
    title:string,
    counter:number,
    color:string,
    icon:ReactElement | null
}
const Counter = ({type,editState,name,handleRemove}:CounterProps) => {
    let content : Content ={
        title:"",
        counter:0,
        color:"",
        icon:null
    }
    switch(type){
        case "devices":
            content = {
                title:"Borrowed Devices",
                counter:20,
                color:"#00adbd",
                icon:<LaptopChromebookRoundedIcon fontSize="inherit"/>
            }
            break;
        case "incidents":
            content = {
                title:"Open Incidents",
                counter:13,
                color: "#f39c12",
                icon:<CalendarMonthRoundedIcon fontSize="inherit"/>
            }
            break;
        case "users":
            content ={
                title:"Users",
                counter:35,
                color: "#8dc155",
                icon: <PeopleAltRoundedIcon fontSize="inherit"/>
            }
            break;
        default:
            break;

    }
  return (
    <WidgetBox editState={editState} height={200} handleRemove={handleRemove} name={name}>
        <div style={{backgroundColor:content.color}} className={styles.top}>
            <p className={styles.counter}>{content.counter}</p>
        </div>
        <div className={styles.body}>
            <h2 style={{color:content.color}} className={styles.title}>{content.title}</h2>
            <div style={{backgroundColor:content.color}} className={styles.icon}>{content.icon}</div>
        </div>
    </WidgetBox>
  )
}

export default Counter