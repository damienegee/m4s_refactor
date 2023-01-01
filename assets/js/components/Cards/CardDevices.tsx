import styles from './cards.module.css';
import { Product } from '../../../types';


const CardsDevices  = ({data}:{data:Product}) => {
    return (<>

        <div className={styles.card}>
            <div className={styles.left}>
                    <div className={styles.row}>
                         Productnummer : 
                    </div>
                    <div className={styles.row}>
                         Serial number : 
                    </div>
                    <div className={styles.row}>
                         model : 
                    </div>
                    <div className={styles.row}>
                         Location : 
                    </div>
                    </div>
            <div className={styles.right}>
                    <div className={styles.row}>
                        {data.productNumber} 
                    </div>
                    <div className={styles.row}>
                        {data.serialNumber} 
                    </div>
                    <div className={styles.row}>
                        {data.serialNumber}
                    </div>
                    <div className={styles.row}>
                        {data.model} 
                    </div>
                    <div className={styles.row}>
                        {data.location}
                    </div>
            </div>
        </div>
    </>)
}
export default CardsDevices;