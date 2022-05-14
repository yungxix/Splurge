import React, {useState, useEffect} from "react";

export interface RenderProps {
    mobile: boolean;
}

export interface LayoutProps {
    children: (props: RenderProps) => any;
}

const isMobile = () => window.innerWidth < 900;

export default function ResponsiveLayout(props: LayoutProps) {
    const [mobile, setMobile] = useState(isMobile());
    useEffect(() => {
        const l = (e: any) => {
                const b = isMobile();
                if (b != mobile) {
                    setMobile(b);
                }
        };
        window.addEventListener('resize', l,false);
        return () => {
            window.removeEventListener('resize', l, false);
        };
    }, []);
 return <>
     {props.children({mobile})}
 </>   
}