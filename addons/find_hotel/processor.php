<?php
/**
 * 找酒店模块处理程序
 *
 * @author john
 * @url http://bbs.012wz.com/
 */
defined('IN_IA') or exit('Access Denied');

class Find_hotelModuleProcessor extends WeModuleProcessor {
	public function respond() {
		$content = $this->message['content'];
		global $_W;
		if (!$this->inContext)
		{
			$this->beginContext(60);

			return $this->respText('点击下方的“+”，发送您的地理位置。然后我们会返回您周边的酒店！');
		}
		else
		{
			if (isset($this->message['location_x']) && isset($this->message['location_y']))
			{
				$this->endContext();
				load()->func('communication');

				$data = ihttp_get("http://api.map.baidu.com/telematics/v3/local?location={$this->message['location_y']},{$this->message['location_x']}&keyWord=酒店&output=json&ak=jhSS7UjKAS9P9h2vDfhacjmr");
				$data = json_decode($data['content'], true);
				if ($data['status'] === 'Success') {
					foreach ($data['pointList'] as $key => $val) {
						if ((int)$key > 7) break;
						$return[] = array(
							'title' => $val['name'],
							'description' => $val['address'],
							'picurl' => '',
							'url' => $val['additionalInformation']['link'][0]['url']
						);
					}
					return $this->respNews($return);

				} else {
					return $this->respText("没有查询结果。");
				}
			}
			else
				$this->endContext();
			return false;
		}
	}
}