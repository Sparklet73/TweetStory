$(document).ready(function () {
    var width = 445,
            height = 420,
            padding = 1.5, // separation between same-color nodes
            clusterPadding = 6, // separation between different-color nodes
            maxRadius = 12;

    var m = 30; //# of clusters

    var color = d3.scale.category10()
            .domain(d3.range(m));

// The largest node for each cluster.
    var clusters = new Array(m);

//    var nodes = d3.range(n).map(function () {
//        var i = Math.floor(Math.random() * m),
//            r = Math.sqrt((i + 1) / m * -Math.log(Math.random())) * maxRadius,
//            d = {cluster: i, radius: r};
//        if (!clusters[i] || (r > clusters[i].radius)) clusters[i] = d;
//        return d;
//    });

    var nodes = [{"cluster": 0, "id": 0, "radius": 28.13235, "name": "政改"}, {"cluster": 0, "id": 1, "radius": 27.21585, "name": "學聯"}, {"cluster": 0, "id": 2, "radius": 25.80375, "name": "政府"}, {"cluster": 0, "id": 3, "radius": 25.45635, "name": "梁振英"}, {"cluster": 0, "id": 4, "radius": 25.21185, "name": "對話"}, {"cluster": 0, "id": 5, "radius": 24.81675, "name": "特首"}, {"cluster": 0, "id": 6, "radius": 24.02265, "name": "表示"}, {"cluster": 0, "id": 7, "radius": 23.60415, "name": "決定"}, {"cluster": 0, "id": 8, "radius": 23.44815, "name": "學生"}, {"cluster": 0, "id": 9, "radius": 22.94295, "name": "要求"}, {"cluster": 1, "id": 10, "radius": 27.61284, "name": "警方"}, {"cluster": 1, "id": 11, "radius": 25.85244, "name": "旺角"}, {"cluster": 1, "id": 12, "radius": 24.01524, "name": "示威者"}, {"cluster": 1, "id": 13, "radius": 23.27964, "name": "清场"}, {"cluster": 1, "id": 14, "radius": 22.98264, "name": "占领"}, {"cluster": 1, "id": 15, "radius": 22.82784, "name": "行动"}, {"cluster": 1, "id": 16, "radius": 22.82124, "name": "人士"}, {"cluster": 1, "id": 17, "radius": 22.77384, "name": "10"}, {"cluster": 1, "id": 18, "radius": 22.60524, "name": "金钟"}, {"cluster": 1, "id": 19, "radius": 21.96234, "name": "集会"}, {"cluster": 2, "id": 20, "radius": 18.79995, "name": "公民"}, {"cluster": 2, "id": 21, "radius": 18.76635, "name": "學生"}, {"cluster": 2, "id": 22, "radius": 18.50445, "name": "抗命"}, {"cluster": 2, "id": 23, "radius": 17.82555, "name": "政府"}, {"cluster": 2, "id": 24, "radius": 17.56815, "name": "和平"}, {"cluster": 2, "id": 25, "radius": 17.52105, "name": "抗爭"}, {"cluster": 2, "id": 26, "radius": 17.48055, "name": "民主"}, {"cluster": 2, "id": 27, "radius": 17.40525, "name": "行動"}, {"cluster": 2, "id": 28, "radius": 17.35365, "name": "爭取"}, {"cluster": 2, "id": 29, "radius": 17.27505, "name": "三子"}, {"cluster": 3, "id": 30, "radius": 25.84092, "name": "警方"}, {"cluster": 3, "id": 31, "radius": 22.81122, "name": "清場"}, {"cluster": 3, "id": 32, "radius": 19.47792, "name": "旺角"}, {"cluster": 3, "id": 33, "radius": 18.78702, "name": "金鐘"}, {"cluster": 3, "id": 34, "radius": 18.72282, "name": "行動"}, {"cluster": 3, "id": 35, "radius": 18.46152, "name": "禁制令"}, {"cluster": 3, "id": 36, "radius": 17.83812, "name": "被捕"}, {"cluster": 3, "id": 37, "radius": 17.13462, "name": "拘捕"}, {"cluster": 3, "id": 38, "radius": 17.03802, "name": "表示"}, {"cluster": 3, "id": 39, "radius": 16.94772, "name": "佔領區"}, {"cluster": 4, "id": 40, "radius": 22.69449, "name": "金鐘"}, {"cluster": 4, "id": 41, "radius": 19.38579, "name": "市民"}, {"cluster": 4, "id": 42, "radius": 18.10389, "name": "旺角"}, {"cluster": 4, "id": 43, "radius": 18.04659, "name": "現場"}, {"cluster": 4, "id": 44, "radius": 17.68779, "name": "清場"}, {"cluster": 4, "id": 45, "radius": 17.67219, "name": "銅鑼灣"}, {"cluster": 4, "id": 46, "radius": 16.84989, "name": "人士"}, {"cluster": 4, "id": 47, "radius": 16.84149, "name": "集會"}, {"cluster": 4, "id": 48, "radius": 16.83969, "name": "警方"}, {"cluster": 4, "id": 49, "radius": 16.74459, "name": "佔領區"}, {"cluster": 5, "id": 50, "radius": 16.84047, "name": "社會"}, {"cluster": 5, "id": 51, "radius": 16.83747, "name": "政治"}, {"cluster": 5, "id": 52, "radius": 16.69257, "name": "民主"}, {"cluster": 5, "id": 53, "radius": 16.01757, "name": "中國"}, {"cluster": 5, "id": 54, "radius": 15.99297, "name": "行動"}, {"cluster": 5, "id": 55, "radius": 15.98397, "name": "支持"}, {"cluster": 5, "id": 56, "radius": 15.80997, "name": "法治"}, {"cluster": 5, "id": 57, "radius": 15.80697, "name": "問題"}, {"cluster": 5, "id": 58, "radius": 15.77367, "name": "政府"}, {"cluster": 5, "id": 59, "radius": 15.71637, "name": "認為"}, {"cluster": 6, "id": 60, "radius": 20.25036, "name": "学生"}, {"cluster": 6, "id": 61, "radius": 19.78146, "name": "学联"}, {"cluster": 6, "id": 62, "radius": 18.68046, "name": "行动"}, {"cluster": 6, "id": 63, "radius": 18.58446, "name": "运动"}, {"cluster": 6, "id": 64, "radius": 17.71626, "name": "对话"}, {"cluster": 6, "id": 65, "radius": 17.28546, "name": "占领"}, {"cluster": 6, "id": 66, "radius": 17.17386, "name": "普选"}, {"cluster": 6, "id": 67, "radius": 17.13486, "name": "罢课"}, {"cluster": 6, "id": 68, "radius": 17.09496, "name": "抗命"}, {"cluster": 6, "id": 69, "radius": 17.09106, "name": "政改"}, {"cluster": 7, "id": 70, "radius": 21.52926, "name": "人士"}, {"cluster": 7, "id": 71, "radius": 20.55816, "name": "反佔中"}, {"cluster": 7, "id": 72, "radius": 19.41786, "name": "警方"}, {"cluster": 7, "id": 73, "radius": 19.29936, "name": "旺角"}, {"cluster": 7, "id": 74, "radius": 18.81666, "name": "警察"}, {"cluster": 7, "id": 75, "radius": 18.19866, "name": "示威者"}, {"cluster": 7, "id": 76, "radius": 17.23986, "name": "暴力"}, {"cluster": 7, "id": 77, "radius": 16.67016, "name": "示威"}, {"cluster": 7, "id": 78, "radius": 16.55256, "name": "市民"}, {"cluster": 7, "id": 79, "radius": 16.50186, "name": "記者"}, {"cluster": 8, "id": 80, "radius": 16.45725, "name": "政改"}, {"cluster": 8, "id": 81, "radius": 16.42515, "name": "梁振英"}, {"cluster": 8, "id": 82, "radius": 16.39005, "name": "中国"}, {"cluster": 8, "id": 83, "radius": 16.14645, "name": "表示"}, {"cluster": 8, "id": 84, "radius": 16.08345, "name": "北京"}, {"cluster": 8, "id": 85, "radius": 15.97515, "name": "政府"}, {"cluster": 8, "id": 86, "radius": 15.96795, "name": "普选"}, {"cluster": 8, "id": 87, "radius": 15.77445, "name": "行动"}, {"cluster": 8, "id": 88, "radius": 15.75915, "name": "中央"}, {"cluster": 8, "id": 89, "radius": 15.75615, "name": "支持"}, {"cluster": 9, "id": 90, "radius": 24.24681, "name": "旺角"}, {"cluster": 9, "id": 91, "radius": 18.70401, "name": "現場"}, {"cluster": 9, "id": 92, "radius": 17.97201, "name": "街道"}, {"cluster": 9, "id": 93, "radius": 17.43741, "name": "市民"}, {"cluster": 9, "id": 94, "radius": 17.38941, "name": "警察"}, {"cluster": 9, "id": 95, "radius": 17.31861, "name": "警方"}, {"cluster": 9, "id": 96, "radius": 17.16591, "name": "警員"}, {"cluster": 9, "id": 97, "radius": 16.30761, "name": "示威者"}, {"cluster": 9, "id": 98, "radius": 15.86691, "name": "PM"}, {"cluster": 9, "id": 99, "radius": 15.56691, "name": "人士"}, {"cluster": 10, "id": 100, "radius": 20.49195, "name": "中国"}, {"cluster": 10, "id": 101, "radius": 16.35675, "name": "运动"}, {"cluster": 10, "id": 102, "radius": 15.85455, "name": "媒体"}, {"cluster": 10, "id": 103, "radius": 15.64515, "name": "支持"}, {"cluster": 10, "id": 104, "radius": 15.58635, "name": "中共"}, {"cluster": 10, "id": 105, "radius": 15.24795, "name": "大陆"}, {"cluster": 10, "id": 106, "radius": 14.90985, "name": "民众"}, {"cluster": 10, "id": 107, "radius": 14.79585, "name": "雨伞"}, {"cluster": 10, "id": 108, "radius": 14.66775, "name": "网络"}, {"cluster": 10, "id": 109, "radius": 14.61825, "name": "10"}, {"cluster": 11, "id": 110, "radius": 26.65776, "name": "罷課"}, {"cluster": 11, "id": 111, "radius": 21.00276, "name": "學生"}, {"cluster": 11, "id": 112, "radius": 14.91816, "name": "集會"}, {"cluster": 11, "id": 113, "radius": 14.17866, "name": "行動"}, {"cluster": 11, "id": 114, "radius": 13.92636, "name": "中學生"}, {"cluster": 11, "id": 115, "radius": 13.87956, "name": "學聯"}, {"cluster": 11, "id": 116, "radius": 13.82496, "name": "參與"}, {"cluster": 11, "id": 117, "radius": 13.70796, "name": "支持"}, {"cluster": 11, "id": 118, "radius": 13.59636, "name": "發起"}, {"cluster": 11, "id": 119, "radius": 12.99816, "name": "學民"}, {"cluster": 12, "id": 120, "radius": 13.89633, "name": "港人"}, {"cluster": 12, "id": 121, "radius": 13.50783, "name": "民主"}, {"cluster": 12, "id": 122, "radius": 12.79203, "name": "大陆"}, {"cluster": 12, "id": 123, "radius": 12.64713, "name": "中国"}, {"cluster": 12, "id": 124, "radius": 12.35493, "name": "中共"}, {"cluster": 12, "id": 125, "radius": 12.34293, "name": "一个"}, {"cluster": 12, "id": 126, "radius": 12.33183, "name": "运动"}, {"cluster": 12, "id": 127, "radius": 12.29763, "name": "抗争"}, {"cluster": 12, "id": 128, "radius": 12.19323, "name": "学生"}, {"cluster": 12, "id": 129, "radius": 12.15933, "name": "普选"}, {"cluster": 13, "id": 130, "radius": 17.4183, "name": "北京"}, {"cluster": 13, "id": 131, "radius": 15.5196, "name": "中央社"}, {"cluster": 13, "id": 132, "radius": 15.1236, "name": "日電"}, {"cluster": 13, "id": 133, "radius": 14.7462, "name": "記者"}, {"cluster": 13, "id": 134, "radius": 14.2623, "name": "今天"}, {"cluster": 13, "id": 135, "radius": 13.8096, "name": "中共"}, {"cluster": 13, "id": 136, "radius": 13.719, "name": "中央"}, {"cluster": 13, "id": 137, "radius": 13.5561, "name": "大陸"}, {"cluster": 13, "id": 138, "radius": 13.104, "name": "中國"}, {"cluster": 13, "id": 139, "radius": 13.0134, "name": "報導"}, {"cluster": 14, "id": 140, "radius": 15.17544, "name": "中国"}, {"cluster": 14, "id": 141, "radius": 13.21254, "name": "民主"}, {"cluster": 14, "id": 142, "radius": 12.85854, "name": "运动"}, {"cluster": 14, "id": 143, "radius": 12.72744, "name": "台湾"}, {"cluster": 14, "id": 144, "radius": 12.20304, "name": "大陆"}, {"cluster": 14, "id": 145, "radius": 12.19224, "name": "北京"}, {"cluster": 14, "id": 146, "radius": 11.96454, "name": "美国"}, {"cluster": 14, "id": 147, "radius": 11.81784, "name": "中共"}, {"cluster": 14, "id": 148, "radius": 11.19774, "name": "一个"}, {"cluster": 14, "id": 149, "radius": 11.05254, "name": "支持"}, {"cluster": 15, "id": 150, "radius": 11.67285, "name": "警察"}, {"cluster": 15, "id": 151, "radius": 11.60175, "name": "反佔中"}, {"cluster": 15, "id": 152, "radius": 11.32125, "name": "一個"}, {"cluster": 15, "id": 153, "radius": 11.19915, "name": "旺角"}, {"cluster": 15, "id": 154, "radius": 10.90095, "name": "覺得"}, {"cluster": 15, "id": 155, "radius": 10.89825, "name": "朋友"}, {"cluster": 15, "id": 156, "radius": 10.89135, "name": "影響"}, {"cluster": 15, "id": 157, "radius": 10.85115, "name": "支持"}, {"cluster": 15, "id": 158, "radius": 10.84965, "name": "有人"}, {"cluster": 15, "id": 159, "radius": 10.69575, "name": "政府"}, {"cluster": 16, "id": 160, "radius": 11.28978, "name": "旺角"}, {"cluster": 16, "id": 161, "radius": 11.26638, "name": "學生"}, {"cluster": 16, "id": 162, "radius": 10.69968, "name": "絕食"}, {"cluster": 16, "id": 163, "radius": 10.63278, "name": "金鐘"}, {"cluster": 16, "id": 164, "radius": 10.15038, "name": "熱血"}, {"cluster": 16, "id": 165, "radius": 10.09638, "name": "一個"}, {"cluster": 16, "id": 166, "radius": 10.06308, "name": "小時"}, {"cluster": 16, "id": 167, "radius": 10.02018, "name": "罷課"}, {"cluster": 16, "id": 168, "radius": 10.01028, "name": "已經"}, {"cluster": 16, "id": 169, "radius": 9.94098, "name": "gt"}, {"cluster": 17, "id": 170, "radius": 13.73871, "name": "反占中"}, {"cluster": 17, "id": 171, "radius": 12.18891, "name": "大陆"}, {"cluster": 17, "id": 172, "radius": 11.02581, "name": "支持"}, {"cluster": 17, "id": 173, "radius": 10.58211, "name": "一个"}, {"cluster": 17, "id": 174, "radius": 10.35681, "name": "中共"}, {"cluster": 17, "id": 175, "radius": 10.25451, "name": "警察"}, {"cluster": 17, "id": 176, "radius": 10.15731, "name": "学生"}, {"cluster": 17, "id": 177, "radius": 10.02561, "name": "人士"}, {"cluster": 17, "id": 178, "radius": 10.02261, "name": "现在"}, {"cluster": 17, "id": 179, "radius": 9.92751, "name": "知道"}, {"cluster": 18, "id": 180, "radius": 14.20818, "name": "中國"}, {"cluster": 18, "id": 181, "radius": 12.59748, "name": "美國"}, {"cluster": 18, "id": 182, "radius": 11.76408, "name": "台灣"}, {"cluster": 18, "id": 183, "radius": 10.99428, "name": "民主"}, {"cluster": 18, "id": 184, "radius": 10.63008, "name": "支持"}, {"cluster": 18, "id": 185, "radius": 9.71658, "name": "行動"}, {"cluster": 18, "id": 186, "radius": 9.69648, "name": "大陸"}, {"cluster": 18, "id": 187, "radius": 9.62568, "name": "表示"}, {"cluster": 18, "id": 188, "radius": 9.45918, "name": "國家"}, {"cluster": 18, "id": 189, "radius": 9.38988, "name": "勢力"}, {"cluster": 19, "id": 190, "radius": 13.29801, "name": "影響"}, {"cluster": 19, "id": 191, "radius": 9.85491, "name": "經濟"}, {"cluster": 19, "id": 192, "radius": 9.59391, "name": "港股"}, {"cluster": 19, "id": 193, "radius": 9.52011, "name": "行動"}, {"cluster": 19, "id": 194, "radius": 9.43791, "name": "新聞網"}, {"cluster": 19, "id": 195, "radius": 9.41511, "name": "持續"}, {"cluster": 19, "id": 196, "radius": 9.15771, "name": "10"}, {"cluster": 19, "id": 197, "radius": 9.13851, "name": "表示"}, {"cluster": 19, "id": 198, "radius": 8.98551, "name": "明鏡"}, {"cluster": 19, "id": 199, "radius": 8.92701, "name": "中國"}, {"cluster": 20, "id": 200, "radius": 10.57206, "name": "中国"}, {"cluster": 20, "id": 201, "radius": 10.28226, "name": "10"}, {"cluster": 20, "id": 202, "radius": 10.12416, "name": "美国"}, {"cluster": 20, "id": 203, "radius": 10.01916, "name": "报道"}, {"cluster": 20, "id": 204, "radius": 9.94266, "name": "支持"}, {"cluster": 20, "id": 205, "radius": 9.60786, "name": "日电"}, {"cluster": 20, "id": 206, "radius": 9.03246, "name": "势力"}, {"cluster": 20, "id": 207, "radius": 8.89236, "name": "主席"}, {"cluster": 20, "id": 208, "radius": 8.71746, "name": "记者"}, {"cluster": 20, "id": 209, "radius": 8.70366, "name": "表示"}, {"cluster": 21, "id": 210, "radius": 25.17021, "name": "2014"}, {"cluster": 21, "id": 211, "radius": 19.59291, "name": "10"}, {"cluster": 21, "id": 212, "radius": 16.20051, "name": "日讯"}, {"cluster": 21, "id": 213, "radius": 14.60511, "name": "唐人"}, {"cluster": 21, "id": 214, "radius": 12.08691, "name": "中国"}, {"cluster": 21, "id": 215, "radius": 11.87931, "name": "11"}, {"cluster": 21, "id": 216, "radius": 10.84401, "name": "0800"}, {"cluster": 21, "id": 217, "radius": 9.98421, "name": "09"}, {"cluster": 21, "id": 218, "radius": 9.95481, "name": "今日"}, {"cluster": 21, "id": 219, "radius": 9.93891, "name": "习近平"}, {"cluster": 22, "id": 220, "radius": 11.35302, "name": "in"}, {"cluster": 22, "id": 221, "radius": 11.25402, "name": "the"}, {"cluster": 22, "id": 222, "radius": 10.94322, "name": "to"}, {"cluster": 22, "id": 223, "radius": 9.96672, "name": "of"}, {"cluster": 22, "id": 224, "radius": 8.90532, "name": "on"}, {"cluster": 22, "id": 225, "radius": 8.76312, "name": "for"}, {"cluster": 22, "id": 226, "radius": 8.70432, "name": "at"}, {"cluster": 22, "id": 227, "radius": 8.56872, "name": "is"}, {"cluster": 22, "id": 228, "radius": 8.16522, "name": "旺角"}, {"cluster": 22, "id": 229, "radius": 8.11962, "name": "police"}, {"cluster": 23, "id": 230, "radius": 12.87387, "name": "公投"}, {"cluster": 23, "id": 231, "radius": 9.28797, "name": "投票"}, {"cluster": 23, "id": 232, "radius": 8.89917, "name": "市民"}, {"cluster": 23, "id": 233, "radius": 8.25987, "name": "資料"}, {"cluster": 23, "id": 234, "radius": 8.05167, "name": "身份證"}, {"cluster": 23, "id": 235, "radius": 7.86087, "name": "反佔中"}, {"cluster": 23, "id": 236, "radius": 7.64847, "name": "參與"}, {"cluster": 23, "id": 237, "radius": 7.63677, "name": "行動"}, {"cluster": 23, "id": 238, "radius": 7.58727, "name": "簽名"}, {"cluster": 23, "id": 239, "radius": 7.56027, "name": "民意"}, {"cluster": 24, "id": 240, "radius": 10.52631, "name": "自由"}, {"cluster": 24, "id": 241, "radius": 9.53061, "name": "中國"}, {"cluster": 24, "id": 242, "radius": 9.35211, "name": "罷課"}, {"cluster": 24, "id": 243, "radius": 9.11961, "name": "台灣"}, {"cluster": 24, "id": 244, "radius": 9.03111, "name": "民主"}, {"cluster": 24, "id": 245, "radius": 8.94981, "name": "公民"}, {"cluster": 24, "id": 246, "radius": 8.49861, "name": "抗命"}, {"cluster": 24, "id": 247, "radius": 8.27961, "name": "學生"}, {"cluster": 24, "id": 248, "radius": 8.02191, "name": "聲援"}, {"cluster": 24, "id": 249, "radius": 7.77951, "name": "時報"}, {"cluster": 25, "id": 250, "radius": 8.84766, "name": "蘋果日報"}, {"cluster": 25, "id": 251, "radius": 8.59086, "name": "新聞"}, {"cluster": 25, "id": 252, "radius": 8.11386, "name": "中國"}, {"cluster": 25, "id": 253, "radius": 7.70406, "name": "中共"}, {"cluster": 25, "id": 254, "radius": 7.56336, "name": "北京"}, {"cluster": 25, "id": 255, "radius": 6.95946, "name": "英國"}, {"cluster": 25, "id": 256, "radius": 6.95016, "name": "即時新聞"}, {"cluster": 25, "id": 257, "radius": 6.89046, "name": "評論"}, {"cluster": 25, "id": 258, "radius": 6.77286, "name": "蘋果"}, {"cluster": 25, "id": 259, "radius": 6.74916, "name": "港聞"}, {"cluster": 26, "id": 260, "radius": 11.74653, "name": "声援"}, {"cluster": 26, "id": 261, "radius": 8.32833, "name": "北京"}, {"cluster": 26, "id": 262, "radius": 7.10223, "name": "人士"}, {"cluster": 26, "id": 263, "radius": 6.98283, "name": "支持"}, {"cluster": 26, "id": 264, "radius": 6.45243, "name": "刑拘"}, {"cluster": 26, "id": 265, "radius": 6.31443, "name": "被捕"}, {"cluster": 26, "id": 266, "radius": 6.18303, "name": "宋庄"}, {"cluster": 26, "id": 267, "radius": 5.87583, "name": "公民"}, {"cluster": 26, "id": 268, "radius": 5.87373, "name": "维权"}, {"cluster": 26, "id": 269, "radius": 5.80923, "name": "律师"}, {"cluster": 27, "id": 270, "radius": 5.54499, "name": "中国"}, {"cluster": 27, "id": 271, "radius": 5.36199, "name": "10"}, {"cluster": 27, "id": 272, "radius": 5.11089, "name": "影响"}, {"cluster": 27, "id": 273, "radius": 4.84599, "name": "经济"}, {"cluster": 27, "id": 274, "radius": 4.76409, "name": "公司"}, {"cluster": 27, "id": 275, "radius": 4.73589, "name": "公布"}, {"cluster": 27, "id": 276, "radius": 4.54299, "name": "2014"}, {"cluster": 27, "id": 277, "radius": 4.50129, "name": "全球"}, {"cluster": 27, "id": 278, "radius": 4.49379, "name": "损失"}, {"cluster": 27, "id": 279, "radius": 4.36809, "name": "日电"}, {"cluster": 28, "id": 280, "radius": 6.38172, "name": "中国"}, {"cluster": 28, "id": 281, "radius": 6.36732, "name": "富豪"}, {"cluster": 28, "id": 282, "radius": 5.47542, "name": "表态"}, {"cluster": 28, "id": 283, "radius": 5.11452, "name": "梁振英"}, {"cluster": 28, "id": 284, "radius": 4.90542, "name": "新华社"}, {"cluster": 28, "id": 285, "radius": 4.84062, "name": "中共"}, {"cluster": 28, "id": 286, "radius": 4.74132, "name": "10"}, {"cluster": 28, "id": 287, "radius": 4.70052, "name": "大陆"}, {"cluster": 28, "id": 288, "radius": 4.65732, "name": "李嘉诚"}, {"cluster": 28, "id": 289, "radius": 4.63212, "name": "行动"}, {"cluster": 29, "id": 290, "radius": 7.69926, "name": "支持"}, {"cluster": 29, "id": 291, "radius": 6.13836, "name": "藝人"}, {"cluster": 29, "id": 292, "radius": 5.69436, "name": "王晶"}, {"cluster": 29, "id": 293, "radius": 5.64966, "name": "示威"}, {"cluster": 29, "id": 294, "radius": 5.52726, "name": "東網"}, {"cluster": 29, "id": 295, "radius": 5.48256, "name": "現場直播"}, {"cluster": 29, "id": 296, "radius": 5.18466, "name": "黃秋生"}, {"cluster": 29, "id": 297, "radius": 5.12946, "name": "抵制"}, {"cluster": 29, "id": 298, "radius": 5.10636, "name": "微博"}, {"cluster": 29, "id": 299, "radius": 5.04636, "name": "何韻詩"}];
    nodes.forEach(function (d) {
        clusters[d.cluster] = d;
    });

    // Use the pack layout to initialize node positions.
    d3.layout.pack()
            .sort(null)
            .size([width, height])
            .children(function (d) {
                return d.values;
            })
            .value(function (d) {
                return d.radius * d.radius;
            })
            .nodes({values: d3.nest()
                        .key(function (d) {
                            return d.cluster;
                        })
                        .entries(nodes)});

    var force = d3.layout.force()
            .nodes(nodes)
            .size([width, height])
            .gravity(.02)
            .charge(0)
            .on("tick", tick)
            .start();

    var svg = d3.select("#keywordsGraph")
            .append("svg")
            .attr("width", width)
            .attr("height", height);

    var node = svg.selectAll("circle")
            .data(nodes)
            .enter().append("circle")
            .style("fill", function (d) {
                return color(d.cluster);
            })
            .call(force.drag);

    var texts = svg.selectAll("text.label")
            .data(nodes)
            .enter().append("text")
            .attr("class", "label")
            .attr("fill", "black")
            .attr("text-anchor", "middle")
            .text(function (d) {
                return d.name;
            });
    //    
//    node.append("text")
//            .attr("x", 12)
//            .attr("dy", ".35em")
//            .text(function (d) {
//                return d.name;
//            });

    node.transition()
            .duration(750)
            .delay(function (d, i) {
                return i * 5;
            })
            .attrTween("r", function (d) {
                var i = d3.interpolate(0, d.radius);
                return function (t) {
                    return d.radius = i(t);
                };
            });

    function tick(e) {
        node.each(cluster(10 * e.alpha * e.alpha))
                .each(collide(.5))
                .attr("cx", function (d) {
                    return d.x;
                })
                .attr("cy", function (d) {
                    return d.y;
                });
        texts.attr("transform", function (d) {
            return "translate(" + d.x + "," + d.y + ")";
        });
    }

// Move d to be adjacent to the cluster node.
    function cluster(alpha) {
        return function (d) {
            var cluster = clusters[d.cluster];
            if (cluster === d)
                return;
            var x = d.x - cluster.x,
                    y = d.y - cluster.y,
                    l = Math.sqrt(x * x + y * y),
                    r = d.radius + cluster.radius;
            if (l != r) {
                l = (l - r) / l * alpha;
                d.x -= x *= l;
                d.y -= y *= l;
                cluster.x += x;
                cluster.y += y;
            }
        };
    }

// Resolves collisions between d and all other circles.
    function collide(alpha) {
        var quadtree = d3.geom.quadtree(nodes);
        return function (d) {
            var r = d.radius + maxRadius + Math.max(padding, clusterPadding),
                    nx1 = d.x - r,
                    nx2 = d.x + r,
                    ny1 = d.y - r,
                    ny2 = d.y + r;
            quadtree.visit(function (quad, x1, y1, x2, y2) {
                if (quad.point && (quad.point !== d)) {
                    var x = d.x - quad.point.x, a
                    y = d.y - quad.point.y,
                            l = Math.sqrt(x * x + y * y),
                            r = d.radius + quad.point.radius + (d.cluster === quad.point.cluster ? padding : clusterPadding);
                    if (l < r) {
                        l = (l - r) / l * alpha;
                        d.x -= x *= l;
                        d.y -= y *= l;
                        quad.point.x += x;
                        quad.point.y += y;
                    }
                }
                return x1 > nx2 || x2 < nx1 || y1 > ny2 || y2 < ny1;
            });
        };
    }
});