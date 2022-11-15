import styles from "./Counter.module.css";
import { ReactElement } from "react";
import LaptopChromebookRoundedIcon from '@mui/icons-material/LaptopChromebookRounded';
import CalendarMonthRoundedIcon from '@mui/icons-material/CalendarMonthRounded';
import PeopleAltRoundedIcon from '@mui/icons-material/PeopleAltRounded';
interface CounterProps{
    type:string
}
interface Content{
    title:string,
    counter:number,
    color:string,
    icon:ReactElement | null
}
const Counter = ({type}:CounterProps) => {
    let content : Content ={
        title:"",
        counter:0,
        color:"",
        icon:null
    }
    switch(type){
        case "devices":
            content = {
                title:"Borrowd Devices",
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
    <div className={styles.container}>
        <div style={{backgroundColor:content.color}} className={styles.top}>
            <p className={styles.counter}>{content.counter}</p>
        </div>
        <div className={styles.body}>
            <h2 style={{color:content.color}} className={styles.title}>{content.title}</h2>
            <div style={{backgroundColor:content.color}} className={styles.icon}>{content.icon}</div>
        </div>
    </div>
  )
}

export default Counter