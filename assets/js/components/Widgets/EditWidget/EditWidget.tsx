import styles from "./EditWidget.module.css";
import {MouseEventHandler} from "react"

interface EditWidgetProps{
  setOpen:(open:boolean) => void
}
const EditWidget = ({setOpen}:EditWidgetProps) => {
  const handleClick : MouseEventHandler<HTMLDivElement> = () => {
    setOpen(true);
  }
  return (
    
    <div className={styles.container} onClick={handleClick}>
        <span className={styles.title}>Edit</span>
    </div>
  )
}

export default EditWidget