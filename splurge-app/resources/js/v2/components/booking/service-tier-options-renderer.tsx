import React, {FC} from 'react';
import { HasText, TierOption } from '../booking-tier-selector';
import Lines from '../lines';
import DOMPurify from 'dompurify';


const hasGroup = (item: TierOption) => item.items && item.items.length > 0;

const ItemsRenderer: FC<{items: HasText[], level: number}> = ({items, level}) => {
    if (items.length === 0) {
        return null;
    }
    return (<ul className='service-tier-options'>
    {
        items.map((item, i) => (<li key={`gl_levl_tier_opt_${level}_${i}`}>
                <span className='arrow'></span>
                {item.text}
                {
                    item.html_text && (<div dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(item.html_text)}}>
                        {/* Render sanitized html */}
                    </div>)
                }
        </li>))
    }

</ul>);
};


export default function ServiceTierOptionsRenderer(props: {
    options: Array<TierOption>
}) {

    const plainItems = props.options.filter(x => !hasGroup(x));

    const withGroups = props.options.filter(x => hasGroup(x));

    

    return <div className='service-tier-options-container'>
        <ItemsRenderer items={plainItems} level={1} />

        {
            withGroups.map((opt, i) => (<div key={`rot_opt_grp_${i}`}>
                <div className='description'>
                    <Lines text={opt.text} />
                    {
                        opt.html_text && (<div dangerouslySetInnerHTML={{__html: DOMPurify.sanitize(opt.html_text)}}>
                            {/* Render sanitized html */}
                        </div>)
                    }
                </div>
                <ItemsRenderer items={opt.items!} level={i + 1} />
            </div>))
        }
        

    </div>
}