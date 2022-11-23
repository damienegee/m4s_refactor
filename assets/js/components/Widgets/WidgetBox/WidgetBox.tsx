import { MouseEventHandler, ReactNode } from "react";
import styles from "./WidgetBox.module.css";
interface WidgetBoxProps{
    children : ReactNode,
    height:number,
    editState:boolean,
    handleRemove : MouseEventHandler<HTMLSpanElement>,
    name:string

}
const WidgetBox = ({children,height,editState,handleRemove,name}:WidgetBoxProps) => {
  return (
    <div style={{height:`${height}px`}} className={styles.container}>
      {editState && <span data-name={name}  className={styles.close} onClick={handleRemove}>x</span>}
        {children}
    </div>
  )
}

export default WidgetBox