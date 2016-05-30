var General =
{
	setCookie : function (name, value, days)
	{
		if (days)
		{
			var date = new Date ();
			date.setTime (date.getTime () + (days * 24 * 60 * 60 * 1000));
			var expires = "; expires=" + date.toGMTString ();
		}
		else
		{
			var expires = "";
		}
		document.cookie = name + "=" + value + expires + "; path=/";
	},

	getCookie : function (name)
	{
		var nameEQ = name + "=";
		var ca = document.cookie.split (";");
		for (var i = 0;i < ca.length;i++)
		{
			var c = ca[i];
			while (c.charAt (0) == " ")
			{
				c = c.substring (1, c.length);
			}
			if (c.indexOf (nameEQ) == 0)
			{
				return c.substring (nameEQ.length, c.length);
			}
		}
		return null;
	},

	deleteCookie : function (name)
	{
		General.setCookie (name, "", -1);
	},

	formatNumber : function (refFloat, dec)
	{
		var decimals = dec == undefined ? 8 : dec;

		var fixed = (Math.round (parseFloat (refFloat) * Math.pow (10, decimals)) / Math.pow (10, decimals)).toString ();
		if (fixed.indexOf (".") == -1)
		{
		  fixed += "." + new Array (decimals + 1).join ("0");
		}
		else
		{
			fixed += new Array (decimals + 2 - fixed.length + fixed.indexOf (".")).join ("0");
		}

		return fixed;
	},

	formatDate : function (refDate)
	{
		var months = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
		var dte = new Date (refDate);
		var year = dte.getFullYear (), month = dte.getMonth (), day = dte.getDate ();

		return day + " " + months[month] + " " + year;
	},

	packForm : function (parentId)
	{
		var packStr = "";
		$("#" + parentId).find ("input, select, textarea").each (function ()
		{
			switch ($(this).attr ("type"))
			{
				case "checkbox":
				case "radio":
					switch (true)
					{
						case $(this).attr ("rel") != null:
							packStr += "&" + $(this).attr ("rel") + "=" + encodeURIComponent ($(this).is (":checked"));
							break;
						case $(this).attr ("id") != null:
							packStr += "&" + $(this).attr ("id") + "=" + encodeURIComponent ($(this).is (":checked"));
							break;
						case $(this).attr ("name") != null:
							packStr += "&" + $(this).attr ("name") + "=" + encodeURIComponent ($(this).is (":checked"));
							break;
					}
					break;
				default:
					switch (true)
					{
						case $(this).attr ("rel") != null:
							packStr += "&" + $(this).attr ("rel") + "=" + encodeURIComponent ($(this).val ());
							break;
						case $(this).attr ("id") != null:
							packStr += "&" + $(this).attr ("id") + "=" + encodeURIComponent ($(this).val ());
							break;
						case $(this).attr ("name") != null:
							packStr += "&" + $(this).attr ("name") + "=" + encodeURIComponent ($(this).val ());
							break;
					}
					break;
			}
		});

		return packStr;
	},


	// parseUri 1.2.2
	// (c) Steven Levithan <stevenlevithan.com>
	// MIT License
	// Modified by Anastas Dolushanov <adolushanov@gmail.com>

	parseURI : function ()
	{
		var o =
		{
			strictMode: false,
			key : ["source","protocol","authority","userInfo","user","password","host","port","relative","path","directory","file","query","anchor"],
			q :
			{
				name : "queryKey",
				parser : /(?:^|&)([^&=]*)=?([^&]*)/g
			},
			parser:
			{
				strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/,
				loose:  /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/)?((?:(([^:@]*)(?::([^:@]*))?)?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/
			}
		},
		m = o.parser[o.strictMode ? "strict" : "loose"].exec (document.location.toString()),
		uri = {},
		i = 14;

		while (i--)
		{
			uri[o.key[i]] = m[i] || "";
		}

		uri[o.q.name] = {};
		uri[o.key[12]].replace (o.q.parser, function ($0, $1, $2)
		{
			if ($1)
			{
				uri[o.q.name][$1] = $2;
			}
		});
		chunks = uri.path.split ("/");
		uri.page = chunks[1];
		uri.subpage = chunks[2];
		uri.mainUrl = uri.protocol + "://" + uri.host + (uri.port != "" ? ":" + uri.port : "") + "/";

		return uri;
	},

}
