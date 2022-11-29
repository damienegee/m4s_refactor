import { ReactNode } from "react";
import Header from "../Header/Header";
import Sidebar from "../Sidebar/Sidebar";
import styles from "./Layout.module.css";

interface LayoutProps {
    title: string,
    editState?:boolean,
    setEditState?:(editState:boolean) => void,
    children:ReactNode
}

const Layout = ({title,editState,setEditState,children}:LayoutProps) => {
  return (
    <div className={styles.container}>
        <Sidebar/>
        <div className={styles.pageContainer}>
            <Header title={title} editState={editState} setEditState={setEditState}/>
            {children}
        </div>
    </div>
  )
}

export default Layout